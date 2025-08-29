<?php

namespace App\Livewire\NotaDinas;

use App\Models\NotaDinas;
use App\Models\NotaDinasParticipant;
use App\Models\User;
use App\Models\Unit;
use App\Models\City;
use App\Services\DocumentNumberService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public NotaDinas $notaDinas;

    // Hapus property signer_user_id dan spt_request_date
    #[Rule('required|exists:units,id')]
    public $requesting_unit_id = '';
    #[Rule('required|exists:users,id')]
    public $to_user_id = '';
    #[Rule('required|exists:users,id')]
    public $from_user_id = '';
    #[Rule('required|exists:cities,id')]
    public $destination_city_id = '';
    #[Rule('required|exists:org_places,id')]
    public $origin_place_id = '';
    #[Rule('required|date')]
    public $nd_date = '';
    #[Rule('required|string|max:255')]
    public $hal = '';
    public $use_custom_signer_title = false;
    #[Rule('nullable|string|max:255')]
    public $custom_signer_title = '';
    #[Rule('required|string')]
    public $dasar = '';
    #[Rule('required|string')]
    public $maksud = '';
    #[Rule('required|integer|min:1')]
    public $lampiran_count = 1;
    #[Rule('required|date')]
    public $start_date = '';
    #[Rule('required|date|after_or_equal:start_date')]
    public $end_date = '';
    #[Rule('required|array|min:1')]
    public $participants = [];
    #[Rule('required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT')]
    public $trip_type = 'LUAR_DAERAH';
    #[Rule('required|in:DRAFT,APPROVED')]
    public $status = 'DRAFT';
    public $sifat = '';
    public $tembusan = '';
    public $notes = '';
    public $number_is_manual = false;
    public $number_manual_reason = '';
    public $manual_doc_no = '';
    public $overlapDetails = [];

    // Livewire-only status toggle with confirmation
    public $confirmingStatusChange = false;
    public $targetStatus = null;

    public function requestStatusChange(string $toStatus): void
    {
        if (!in_array($toStatus, ['DRAFT', 'APPROVED'], true)) {
            return;
        }
        if ($toStatus === $this->status) {
            return;
        }
        $this->targetStatus = $toStatus;
        $this->confirmingStatusChange = true;
    }

    public function cancelStatusChange(): void
    {
        $this->confirmingStatusChange = false;
        $this->targetStatus = null;
    }

    public function applyStatusChange(): void
    {
        if ($this->targetStatus && in_array($this->targetStatus, ['DRAFT', 'APPROVED'], true)) {
            $this->status = $this->targetStatus;
        }
        $this->confirmingStatusChange = false;
        $this->targetStatus = null;
    }

    public function mount(NotaDinas $notaDinas)
    {
        $this->notaDinas = $notaDinas;
        $this->requesting_unit_id = $notaDinas->requesting_unit_id;
        $this->to_user_id = $notaDinas->to_user_id;
        $this->from_user_id = $notaDinas->from_user_id;
        $this->destination_city_id = $notaDinas->destination_city_id;
        $this->origin_place_id = $notaDinas->origin_place_id;
        $this->nd_date = $notaDinas->nd_date;
        $this->hal = $notaDinas->hal;
        $this->custom_signer_title = $notaDinas->custom_signer_title;
        $this->use_custom_signer_title = !empty($notaDinas->custom_signer_title);
        $this->dasar = $notaDinas->dasar;
        $this->maksud = $notaDinas->maksud;
        $this->lampiran_count = $notaDinas->lampiran_count;
        $this->start_date = $notaDinas->start_date;
        $this->end_date = $notaDinas->end_date;
        $this->trip_type = $notaDinas->trip_type;
        $this->participants = $notaDinas->participants()->pluck('user_id')->toArray();
        $this->status = $notaDinas->status;
        $this->sifat = $notaDinas->sifat;
        $this->tembusan = $notaDinas->tembusan;
        $this->notes = $notaDinas->notes;
        $this->number_is_manual = $notaDinas->number_is_manual;
        $this->manual_doc_no = $notaDinas->doc_no;
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            // Validasi transisi status yang diizinkan
            $allowedTransitions = [
                'DRAFT' => ['APPROVED'],
                'APPROVED' => ['DRAFT'],
            ];
            $currentStatus = $this->notaDinas->status;
            $allowedNextStatuses = $allowedTransitions[$currentStatus] ?? [];
            if ($this->status !== $currentStatus && !in_array($this->status, $allowedNextStatuses, true)) {
                $this->addError('status', 'Transisi status tidak diizinkan.');
                DB::rollBack();
                return;
            }


            $doc_no = $this->notaDinas->doc_no;
            $number_is_manual = false;
            $number_manual_reason = null;
            // Jika override nomor manual
            if ($this->number_is_manual && $this->manual_doc_no && $this->manual_doc_no !== $this->notaDinas->doc_no) {
                $doc_no = $this->manual_doc_no;
                $number_is_manual = true;
                $number_manual_reason = $this->number_manual_reason;
                // Audit override
                \App\Services\DocumentNumberService::override('ND', $this->notaDinas->id, $doc_no, $number_manual_reason, auth()->id(), [
                    'old_number' => $this->notaDinas->doc_no,
                    'format_id' => $this->notaDinas->number_format_id,
                    'sequence_id' => $this->notaDinas->number_sequence_id,
                ]);
            }
            // Validasi overlap peserta (kecuali ND ini sendiri)
            $overlapDetails = [];
            
            // Debug: Log input data
            Log::info('Checking overlap for participants (EDIT):', [
                'participants' => $this->participants,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'current_nd_id' => $this->notaDinas->id
            ]);
            
            foreach ($this->participants as $userId) {
                // Debug: Check if user exists
                $user = User::with(['position', 'unit'])->find($userId);
                Log::info("Checking user (EDIT): {$userId}", [
                    'user_exists' => $user ? true : false,
                    'user_name' => $user ? $user->name : 'Not found'
                ]);
                
                $overlaps = NotaDinasParticipant::where('user_id', $userId)
                    ->whereHas('notaDinas', function($q) {
                        $q->where('id', '!=', $this->notaDinas->id)
                            ->where(function($q2) {
                                $q2->where('start_date', '<=', $this->end_date)
                                    ->where('end_date', '>=', $this->start_date);
                            });
                    })
                    ->with(['notaDinas' => function($q) {
                        $q->select('id', 'doc_no', 'hal', 'start_date', 'end_date', 'requesting_unit_id')
                          ->with(['requestingUnit:id,name']);
                    }])
                    ->get();
                
                // Debug: Log query results
                Log::info("Overlaps found for user {$userId} (EDIT):", [
                    'count' => $overlaps->count(),
                    'overlaps' => $overlaps->toArray()
                ]);
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
            if (!empty($overlapDetails)) {
                // Debug: Log overlap details
                Log::info('Overlap Details:', $overlapDetails);
                
                $this->overlapDetails = $overlapDetails;
                $this->dispatch('showOverlapAlert', $overlapDetails);
                $this->addError('participants', 'Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain.');
                DB::rollBack();
                return;
            } else {
                $this->overlapDetails = [];
            }
            $this->notaDinas->update([
                'doc_no' => $doc_no,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'number_scope_unit_id' => $this->requesting_unit_id,
                'to_user_id' => $this->to_user_id,
                'from_user_id' => $this->from_user_id,
                'tembusan' => $this->tembusan,
                'nd_date' => $this->nd_date,
                'sifat' => $this->sifat,
                'lampiran_count' => $this->lampiran_count,
                'hal' => $this->hal,
                'custom_signer_title' => $this->use_custom_signer_title ? $this->custom_signer_title : null,
                'dasar' => $this->dasar,
                'maksud' => $this->maksud,
                'destination_city_id' => $this->destination_city_id,
                'origin_place_id' => $this->origin_place_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'trip_type' => $this->trip_type,
                'requesting_unit_id' => $this->requesting_unit_id,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            // Update snapshot of user data only if it doesn't exist yet
            if (!$this->notaDinas->from_user_name_snapshot || !$this->notaDinas->to_user_name_snapshot) {
                $this->notaDinas->createUserSnapshot();
            }

            // Update peserta - hanya buat snapshot untuk participant baru
            $existingParticipantIds = $this->notaDinas->participants()->pluck('user_id')->toArray();
            $newParticipantIds = array_diff($this->participants, $existingParticipantIds);
            
            // Hapus participant yang tidak ada di list baru
            $this->notaDinas->participants()->whereNotIn('user_id', $this->participants)->delete();
            
            // Tambah participant baru
            foreach ($newParticipantIds as $userId) {
                $participant = NotaDinasParticipant::create([
                    'nota_dinas_id' => $this->notaDinas->id,
                    'user_id' => $userId,
                ]);
                // Create snapshot hanya untuk participant baru
                $participant->createUserSnapshot();
            }
            DB::commit();
            session()->flash('message', 'Nota Dinas berhasil diperbarui.');
            // Redirect ke halaman utama dengan state yang sama
            return $this->redirect(route('documents', [
                'nota_dinas_id' => $this->notaDinas->id
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Gagal menyimpan Nota Dinas: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
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
        $orgPlaces = \App\Models\OrgPlace::orderBy('name')->get();
        return view('livewire.nota-dinas.edit', [
            'units' => $units,
            'users' => $users,
            'cities' => $cities,
            'orgPlaces' => $orgPlaces,
        ]);
    }
}
