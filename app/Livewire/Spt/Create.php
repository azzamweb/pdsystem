<?php

namespace App\Livewire\Spt;

use App\Models\Spt;
use App\Models\NotaDinas;
use App\Models\User;
use App\Models\Unit;
use App\Services\DocumentNumberService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $nota_dinas_id = null;
    public $notaDinas = null;

    #[Rule('required|exists:units,id')]
    public $requesting_unit_id = '';
    #[Rule('required|exists:users,id')]
    public $to_user_id = '';
    #[Rule('required|exists:users,id')]
    public $from_user_id = '';
    #[Rule('required|date')]
    public $spt_date = '';
    #[Rule('required|string|max:255')]
    public $hal = '';
    #[Rule('required|string')]
    public $dasar = '';
    #[Rule('required|string')]
    public $maksud = '';
    #[Rule('required|array|min:1')]
    public $members = [];
    #[Rule('required|in:DRAFT,SUBMITTED,APPROVED,REJECTED')]
    public $status = 'DRAFT';
    public $notes = '';
    public $number_is_manual = false;
    public $number_manual_reason = '';
    public $manual_doc_no = '';

    public function mount($nota_dinas_id = null)
    {
        $this->nota_dinas_id = $nota_dinas_id;
        
        if ($this->nota_dinas_id) {
            $this->notaDinas = NotaDinas::with(['participants.user', 'requestingUnit', 'toUser', 'fromUser'])->findOrFail($this->nota_dinas_id);
            
            // Pre-fill form dengan data dari Nota Dinas
            $this->requesting_unit_id = $this->notaDinas->requesting_unit_id;
            $this->to_user_id = $this->notaDinas->to_user_id;
            $this->from_user_id = $this->notaDinas->from_user_id;
            $this->hal = $this->notaDinas->hal;
            $this->dasar = $this->notaDinas->dasar;
            $this->maksud = $this->notaDinas->maksud;
            $this->spt_date = now()->format('Y-m-d');
            
            // Pre-select members dari Nota Dinas participants
            $this->members = $this->notaDinas->participants->pluck('user_id')->toArray();
        }
    }

    public function save()
    {
        $this->validate();
        
        DB::beginTransaction();
        try {
            // Generate nomor dokumen
            $doc_no = null;
            $number_is_manual = false;
            $number_manual_reason = null;
            
            if ($this->number_is_manual && $this->manual_doc_no) {
                $doc_no = $this->manual_doc_no;
                $number_is_manual = true;
                $number_manual_reason = $this->number_manual_reason;
                // Audit override
                DocumentNumberService::override('SPT', null, $doc_no, $number_manual_reason, auth()->id(), [
                    'nota_dinas_id' => $this->nota_dinas_id,
                ]);
            } else {
                // Generate otomatis
                $doc_no = DocumentNumberService::generate('SPT', auth()->id(), [
                    'nota_dinas_id' => $this->nota_dinas_id,
                    'unit_scope_id' => $this->requesting_unit_id,
                ]);
            }

            // Create SPT
            $spt = Spt::create([
                'doc_no' => $doc_no,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'nota_dinas_id' => $this->nota_dinas_id,
                'requesting_unit_id' => $this->requesting_unit_id,
                'to_user_id' => $this->to_user_id,
                'from_user_id' => $this->from_user_id,
                'spt_date' => $this->spt_date,
                'hal' => $this->hal,
                'dasar' => $this->dasar,
                'maksud' => $this->maksud,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            // Create SPT members
            foreach ($this->members as $userId) {
                $spt->members()->create([
                    'user_id' => $userId,
                ]);
            }

            DB::commit();
            
            session()->flash('message', 'SPT berhasil dibuat.');
            return $this->redirect(route('spt.show', $spt));
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal membuat SPT: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        return view('livewire.spt.create', [
            'units' => $units,
            'users' => $users,
        ]);
    }
}
