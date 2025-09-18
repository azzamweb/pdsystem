<?php

namespace App\Http\Controllers;

use App\Models\NotaDinas;
use App\Models\NotaDinasParticipant;
use App\Models\User;
use App\Models\Unit;
use App\Models\City;
use App\Models\OrgPlace;
use App\Services\DocumentNumberService;
use App\Http\Requests\NotaDinasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PermissionHelper;

class NotaDinasController extends Controller
{
    public function create()
    {
        $units = Unit::orderBy('name')->get();
        
        $users = User::query()
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            ->leftJoin('echelons', 'echelons.id', '=', 'positions.echelon_id')
            ->leftJoin('ranks', 'ranks.id', '=', 'users.rank_id')
            ->orderByRaw('COALESCE(echelons.id, 999999) ASC')
            ->orderByRaw('COALESCE(ranks.id, 0) DESC')
            ->orderBy('users.nip', 'ASC')
            ->select('users.*')
            ->get();
            
        $cities = City::orderBy('name')->get();
        $orgPlaces = OrgPlace::orderBy('name')->get();
        
        return view('nota-dinas.create', compact('units', 'users', 'cities', 'orgPlaces'));
    }

    public function store(NotaDinasRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Validasi overlap peserta
            $overlapDetails = $this->checkParticipantOverlaps(
                $request->participants,
                $request->start_date,
                $request->end_date
            );
            
            if (!empty($overlapDetails)) {
                DB::rollBack(); // Important: rollback transaction before redirect
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['participants' => 'Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain.'])
                    ->with('overlap_details', $overlapDetails);
            }

            // Generate document number
            $doc_no = $request->doc_no;
            $number_is_manual = $request->boolean('number_is_manual');
            $number_manual_reason = $request->number_manual_reason;
            $format_id = null;
            $sequence_id = null;
            
            if (!$number_is_manual) {
                $numberResult = DocumentNumberService::generate('ND', $request->requesting_unit_id, $request->nd_date, [], $request->user()->id);
                $doc_no = $numberResult['number'];
                $format_id = $numberResult['format']?->id ?? null;
                $sequence_id = $numberResult['sequence']?->id ?? null;
            } else {
                DocumentNumberService::override('ND', null, $doc_no, $number_manual_reason, $request->user()->id, []);
            }

            // Create Nota Dinas
            $notaDinas = NotaDinas::create([
                'doc_no' => $doc_no,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'number_format_id' => $format_id,
                'number_sequence_id' => $sequence_id,
                'number_scope_unit_id' => $request->requesting_unit_id,
                'to_user_id' => $request->to_user_id,
                'from_user_id' => $request->from_user_id,
                'tembusan' => $request->tembusan,
                'nd_date' => $request->nd_date,
                'sifat' => $request->sifat,
                'lampiran_count' => $request->lampiran_count,
                'hal' => $request->hal,
                'custom_signer_title' => $request->boolean('use_custom_signer_title') ? $request->custom_signer_title : null,
                'dasar' => $request->dasar,
                'maksud' => $request->maksud,
                'destination_city_id' => $request->destination_city_id,
                'origin_place_id' => $request->origin_place_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'trip_type' => $request->trip_type,
                'requesting_unit_id' => $request->requesting_unit_id,
                'status' => $request->status,
                'created_by' => $request->user()->id,
                'notes' => $request->notes,
            ]);

            // Create participants
            foreach ($request->participants as $userId) {
                $participant = NotaDinasParticipant::create([
                    'nota_dinas_id' => $notaDinas->id,
                    'user_id' => $userId,
                ]);
                $participant->createUserSnapshot();
            }

            $notaDinas->createUserSnapshot();
            DB::commit();
            
            return redirect()->route('documents', ['nota_dinas_id' => $notaDinas->id])
                ->with('message', 'Nota Dinas berhasil dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Nota Dinas: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Gagal menyimpan Nota Dinas: ' . $e->getMessage()]);
        }
    }

    public function edit(NotaDinas $notaDinas)
    {
        $units = Unit::orderBy('name')->get();
        
        $users = User::query()
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            ->leftJoin('echelons', 'echelons.id', '=', 'positions.echelon_id')
            ->leftJoin('ranks', 'ranks.id', '=', 'users.rank_id')
            ->orderByRaw('COALESCE(echelons.id, 999999) ASC')
            ->orderByRaw('COALESCE(ranks.id, 0) DESC')
            ->orderBy('users.name')
            ->select('users.*')
            ->get();
            
        $cities = City::orderBy('name')->get();
        $orgPlaces = OrgPlace::orderBy('name')->get();
        
        return view('nota-dinas.edit', compact('notaDinas', 'units', 'users', 'cities', 'orgPlaces'));
    }

    public function update(NotaDinasRequest $request, NotaDinas $notaDinas)
    {
        try {
            DB::beginTransaction();
            
            // Validasi transisi status
            $allowedTransitions = [
                'DRAFT' => ['APPROVED'],
                'APPROVED' => ['DRAFT'],
            ];
            $currentStatus = $notaDinas->status;
            $allowedNextStatuses = $allowedTransitions[$currentStatus] ?? [];
            
            if ($request->status !== $currentStatus && !in_array($request->status, $allowedNextStatuses, true)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['status' => 'Transisi status tidak diizinkan.']);
            }

            // Handle document number override
            $doc_no = $notaDinas->doc_no;
            $number_is_manual = false;
            $number_manual_reason = null;
            
            if ($request->boolean('number_is_manual') && $request->manual_doc_no && $request->manual_doc_no !== $notaDinas->doc_no) {
                $doc_no = $request->manual_doc_no;
                $number_is_manual = true;
                $number_manual_reason = $request->number_manual_reason;
                
                DocumentNumberService::override('ND', $notaDinas->id, $doc_no, $number_manual_reason, $request->user()->id, [
                    'old_number' => $notaDinas->doc_no,
                    'format_id' => $notaDinas->number_format_id,
                    'sequence_id' => $notaDinas->number_sequence_id,
                ]);
            }

            // Validasi overlap peserta
            $overlapDetails = $this->checkParticipantOverlaps(
                $request->participants,
                $request->start_date,
                $request->end_date,
                $notaDinas->id
            );
            
            if (!empty($overlapDetails)) {
                DB::rollBack(); // Important: rollback transaction before redirect
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['participants' => 'Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain.'])
                    ->with('overlap_details', $overlapDetails);
            }

            // Update Nota Dinas
            $notaDinas->update([
                'doc_no' => $doc_no,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'number_scope_unit_id' => $request->requesting_unit_id,
                'to_user_id' => $request->to_user_id,
                'from_user_id' => $request->from_user_id,
                'tembusan' => $request->tembusan,
                'nd_date' => $request->nd_date,
                'sifat' => $request->sifat,
                'lampiran_count' => $request->lampiran_count,
                'hal' => $request->hal,
                'custom_signer_title' => $request->boolean('use_custom_signer_title') ? $request->custom_signer_title : null,
                'dasar' => $request->dasar,
                'maksud' => $request->maksud,
                'destination_city_id' => $request->destination_city_id,
                'origin_place_id' => $request->origin_place_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'trip_type' => $request->trip_type,
                'requesting_unit_id' => $request->requesting_unit_id,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            $notaDinas->refresh();

            // Update snapshot if needed
            if ($notaDinas->from_user_id !== $request->from_user_id || 
                $notaDinas->to_user_id !== $request->to_user_id || 
                !$notaDinas->from_user_name_snapshot || 
                !$notaDinas->to_user_name_snapshot) {
                $notaDinas->createUserSnapshot();
            }

            // Update participants
            $existingParticipantIds = $notaDinas->participants()->pluck('user_id')->toArray();
            $newParticipantIds = array_diff($request->participants, $existingParticipantIds);
            
            $notaDinas->participants()->whereNotIn('user_id', $request->participants)->delete();
            
            foreach ($newParticipantIds as $userId) {
                $participant = NotaDinasParticipant::create([
                    'nota_dinas_id' => $notaDinas->id,
                    'user_id' => $userId,
                ]);
                $participant->createUserSnapshot();
            }

            DB::commit();
            
            return redirect()->route('documents', ['nota_dinas_id' => $notaDinas->id])
                ->with('message', 'Nota Dinas berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Nota Dinas: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['general' => 'Gagal menyimpan Nota Dinas: ' . $e->getMessage()]);
        }
    }

    private function checkParticipantOverlaps($participants, $startDate, $endDate, $excludeId = null)
    {
        $overlapDetails = [];
        
        foreach ($participants as $userId) {
            $user = User::with(['position', 'unit'])->find($userId);
            
            $query = NotaDinasParticipant::where('user_id', $userId)
                ->whereHas('notaDinas', function($q) use ($startDate, $endDate, $excludeId) {
                    $q->where(function($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $endDate)
                            ->where('end_date', '>=', $startDate);
                    });
                    if ($excludeId) {
                        $q->where('id', '!=', $excludeId);
                    }
                })
                ->with(['notaDinas' => function($q) {
                    $q->select('id', 'doc_no', 'hal', 'start_date', 'end_date', 'requesting_unit_id')
                      ->with(['requestingUnit:id,name']);
                }]);
            
            $overlaps = $query->get();
            
            if ($overlaps->count() > 0) {
                foreach ($overlaps as $ov) {
                    $userInfo = $user ? $user->fullNameWithTitles() . ' (' . trim(($user->position->name ?? '') . ' ' . ($user->unit->name ?? '')) . ')' : 'User ID: ' . $userId;
                    $overlapDetails[] = [
                        'user' => $userInfo,
                        'doc_no' => $ov->notaDinas->doc_no ?? '-',
                        'hal' => $ov->notaDinas->hal ?? '-',
                        'unit' => $ov->notaDinas->requestingUnit->name ?? '-',
                        'start_date' => $ov->notaDinas->start_date ?? null,
                        'end_date' => $ov->notaDinas->end_date ?? null,
                    ];
                }
            }
        }
        
        return $overlapDetails;
    }

    public function generatePdf(NotaDinas $notaDinas)
    {
        // Check if user can view nota dinas
        if (!PermissionHelper::can('nota-dinas.view')) {
            abort(403, 'Anda tidak memiliki izin untuk melihat nota dinas.');
        }

        // Check unit scope for bendahara pengeluaran pembantu
        if (!PermissionHelper::canAccessAllData()) {
            $userUnitId = PermissionHelper::getUserUnitId();
            if ($userUnitId) {
                $hasAccess = $notaDinas->participants()
                    ->where('unit_id', $userUnitId)
                    ->exists();
                
                if (!$hasAccess) {
                    abort(403, 'Anda hanya dapat melihat nota dinas dari bidang Anda.');
                }
            }
        }

        // Load relationships yang diperlukan
        $notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser', 'spt']);
        
        // Generate PDF
        $pdf = Pdf::loadView('nota-dinas.pdf', [
            'notaDinas' => $notaDinas
        ]);
        
        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set options untuk hasil yang lebih baik
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'chroot' => public_path(),
        ]);
        
        // Tampilkan preview PDF di browser menggunakan stream() dengan Attachment => false
        $filename = 'Nota_Dinas_' . str_replace(['/', '\\'], '-', $notaDinas->doc_no) . '.pdf';
        
        return $pdf->stream("$filename", ["Attachment" => false]);
    }
    

    
    public function downloadPdf(NotaDinas $notaDinas)
    {
        // Check if user can view nota dinas
        if (!PermissionHelper::can('nota-dinas.view')) {
            abort(403, 'Anda tidak memiliki izin untuk melihat nota dinas.');
        }

        // Check unit scope for bendahara pengeluaran pembantu
        if (!PermissionHelper::canAccessAllData()) {
            $userUnitId = PermissionHelper::getUserUnitId();
            if ($userUnitId) {
                $hasAccess = $notaDinas->participants()
                    ->where('unit_id', $userUnitId)
                    ->exists();
                
                if (!$hasAccess) {
                    abort(403, 'Anda hanya dapat melihat nota dinas dari bidang Anda.');
                }
            }
        }

        // Load relationships yang diperlukan
        $notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser', 'spt']);
        
        // Generate PDF
        $pdf = Pdf::loadView('nota-dinas.pdf', [
            'notaDinas' => $notaDinas
        ]);
        
        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set options untuk hasil yang lebih baik
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'chroot' => public_path(),
        ]);
        
        // Download PDF dengan nama file yang sesuai
        $filename = 'Nota_Dinas_' . str_replace(['/', '\\'], '-', $notaDinas->doc_no) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
