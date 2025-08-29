<?php

namespace App\Livewire\NotaDinas;

use App\Models\NotaDinas;
use App\Models\NotaDinasParticipant;
use App\Models\User;
use App\Models\Unit;
use App\Models\City;
use App\Models\OrgPlace;
use App\Services\DocumentNumberService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class Create extends Component
{
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
    #[Rule('nullable|string|max:255')]
    public $doc_no = '';
    public $number_is_manual = false;
    public $number_manual_reason = '';
    #[Rule('required|in:Penting,Segera,Biasa,Rahasia')]
    public $sifat = 'Penting';
    #[Rule('required|date')]
    public $nd_date = '';
    #[Rule('required|string|max:255')]
    public $hal = '';
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
    public $tembusan = '';
    public $notes = '';
    public $overlapDetails = [];

    public function save()
    {
        try {
            $rules = [
                'requesting_unit_id' => 'required|exists:units,id',
                'to_user_id' => 'required|exists:users,id',
                'from_user_id' => 'required|exists:users,id',
                'destination_city_id' => 'required|exists:cities,id',
                'origin_place_id' => 'required|exists:org_places,id',
                'sifat' => 'required|in:Penting,Segera,Biasa,Rahasia',
                'nd_date' => 'required|date',
                'hal' => 'required|string|max:255',
                'dasar' => 'required|string',
                'maksud' => 'required|string',
                'lampiran_count' => 'required|integer|min:1',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'participants' => 'required|array|min:1',
                'participants.*' => 'exists:users,id',
                'trip_type' => 'required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT',
                'status' => 'required|in:DRAFT,SUBMITTED',
                'tembusan' => 'nullable|string',
                'notes' => 'nullable|string',
            ];
            if ($this->number_is_manual) {
                $rules['doc_no'] = 'required|string|unique:nota_dinas,doc_no';
                $rules['number_manual_reason'] = 'required|string|max:255';
            } else {
                $rules['doc_no'] = 'nullable|string';
                $rules['number_manual_reason'] = 'nullable|string|max:255';
            }
            $this->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('general', 'Validasi gagal: ' . implode(' ', $e->validator->errors()->all()));
            return;
        }
        DB::beginTransaction();
        try {
            // Validasi overlap peserta
            $overlapDetails = [];
            
            // Debug: Log input data
            Log::info('Checking overlap for participants:', [
                'participants' => $this->participants,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date
            ]);
            
            foreach ($this->participants as $userId) {
                // Debug: Check if user exists
                $user = User::with(['position', 'unit'])->find($userId);
                Log::info("Checking user: {$userId}", [
                    'user_exists' => $user ? true : false,
                    'user_name' => $user ? $user->name : 'Not found'
                ]);
                
                $overlaps = NotaDinasParticipant::where('user_id', $userId)
                    ->whereHas('notaDinas', function($q) {
                        $q->where(function($q2) {
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
                Log::info("Overlaps found for user {$userId}:", [
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
                return;
            } else {
                $this->overlapDetails = [];
            }

            $doc_no = $this->doc_no;
            $number_is_manual = $this->number_is_manual;
            $number_manual_reason = $this->number_manual_reason;
            $format_id = null;
            $sequence_id = null;
            if (!$this->number_is_manual) {
                $numberResult = DocumentNumberService::generate('ND', $this->requesting_unit_id, $this->nd_date, [], auth()->id());
                $doc_no = $numberResult['number'];
                $format_id = $numberResult['format']->id;
                $sequence_id = $numberResult['sequence']->id;
            } else {
                DocumentNumberService::override('ND', null, $doc_no, $number_manual_reason, auth()->id(), []);
            }
            $nd = NotaDinas::create([
                'doc_no' => $doc_no,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'number_format_id' => $format_id,
                'number_sequence_id' => $sequence_id,
                'number_scope_unit_id' => $this->requesting_unit_id,
                'to_user_id' => $this->to_user_id,
                'from_user_id' => $this->from_user_id,
                'tembusan' => $this->tembusan,
                'nd_date' => $this->nd_date,
                'sifat' => $this->sifat,
                'lampiran_count' => $this->lampiran_count,
                'hal' => $this->hal,
                'dasar' => $this->dasar,
                'maksud' => $this->maksud,
                'destination_city_id' => $this->destination_city_id,
                'origin_place_id' => $this->origin_place_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'trip_type' => $this->trip_type,
                'requesting_unit_id' => $this->requesting_unit_id,
                'status' => $this->status,
                'created_by' => auth()->id(),
                'notes' => $this->notes,
            ]);
            foreach ($this->participants as $userId) {
                NotaDinasParticipant::create([
                    'nota_dinas_id' => $nd->id,
                    'user_id' => $userId,
                ]);
            }
            DB::commit();
            session()->flash('message', 'Nota Dinas berhasil dibuat.');
            // Redirect ke halaman utama dengan state yang sama
            return $this->redirect(route('documents', [
                'nota_dinas_id' => $nd->id
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Gagal menyimpan Nota Dinas: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan Nota Dinas: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        // Urutan: Eselon (posisi) lalu Pangkat (rank), kemudian nama
        $users = User::query()
            ->leftJoin('positions', 'positions.id', '=', 'users.position_id')
            ->leftJoin('echelons', 'echelons.id', '=', 'positions.echelon_id')
            ->leftJoin('ranks', 'ranks.id', '=', 'users.rank_id')
            // Eselon yang tidak ada diurutkan terakhir (angka besar)
            ->orderByRaw('COALESCE(echelons.id, 999999) ASC')
            // Rank lebih tinggi muncul lebih dulu; jika tidak ada rank, ditempatkan paling bawah
            ->orderByRaw('COALESCE(ranks.id, 0) DESC')
            ->orderBy('users.name')
            ->select('users.*')
            ->get();
        $cities = City::orderBy('name')->get();
        return view('livewire.nota-dinas.create', [
            'units' => $units,
            'users' => $users,
            'cities' => $cities,
        ]);
    }
}
