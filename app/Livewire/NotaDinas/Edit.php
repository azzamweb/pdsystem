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
    #[Rule('required|in:DRAFT,SUBMITTED,APPROVED,REJECTED')]
    public $status = 'DRAFT';
    public $sifat = '';
    public $tembusan = '';
    public $notes = '';
    public $number_is_manual = false;
    public $number_manual_reason = '';
    public $manual_doc_no = '';

    public function mount(NotaDinas $notaDinas)
    {
        $this->notaDinas = $notaDinas;
        $this->requesting_unit_id = $notaDinas->requesting_unit_id;
        $this->to_user_id = $notaDinas->to_user_id;
        $this->from_user_id = $notaDinas->from_user_id;
        $this->destination_city_id = $notaDinas->destination_city_id;
        $this->nd_date = $notaDinas->nd_date;
        $this->hal = $notaDinas->hal;
        $this->dasar = $notaDinas->dasar;
        $this->maksud = $notaDinas->maksud;
        $this->lampiran_count = $notaDinas->lampiran_count;
        $this->start_date = $notaDinas->start_date;
        $this->end_date = $notaDinas->end_date;
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
            $days_count = (\Carbon\Carbon::parse($this->start_date)->diffInDays(\Carbon\Carbon::parse($this->end_date))) + 1;
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
                'dasar' => $this->dasar,
                'maksud' => $this->maksud,
                'destination_city_id' => $this->destination_city_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'days_count' => $days_count,
                'requesting_unit_id' => $this->requesting_unit_id,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);
            // Update peserta
            $this->notaDinas->participants()->delete();
            foreach ($this->participants as $userId) {
                NotaDinasParticipant::create([
                    'nota_dinas_id' => $this->notaDinas->id,
                    'user_id' => $userId,
                ]);
            }
            DB::commit();
            session()->flash('message', 'Nota Dinas berhasil diperbarui.');
            return $this->redirect(route('nota-dinas.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Gagal menyimpan Nota Dinas: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        return view('livewire.nota-dinas.edit', [
            'units' => $units,
            'users' => $users,
            'cities' => $cities,
        ]);
    }
}
