<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use App\Models\Sppd;
use App\Models\User;
use App\Services\DocumentNumberService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $sppd_id = null;
    public $spt_id = null;
    public $sppd = null;
    public $spt = null;

    #[Rule('nullable|string')]
    public $account_code = '';

    #[Rule('required|exists:users,id')]
    public $treasurer_user_id = '';

    #[Rule('required|in:Bendahara Pengeluaran,Bendahara Pengeluaran Pembantu')]
    public $treasurer_title = '';

    #[Rule('required|date')]
    public $receipt_date = '';

    public $receipt_no = '';

    // Available users for selection
    public $approvalUsers = [];
    public $treasurerUsers = [];
    
    // Available SPPDs for selection
    public $availableSppds = [];

    public function mount($sppd_id = null): void
    {
        $this->sppd_id = $sppd_id ?? request()->query('sppd_id');
        $this->spt_id = request()->query('spt_id');
        
        // If we have spt_id but no sppd_id, load available SPPDs
        if ($this->spt_id && !$this->sppd_id) {
            $this->loadAvailableSppds();
            return;
        }
        
        // If we have sppd_id, load the SPPD
        if ($this->sppd_id) {
            $this->loadSppdData();
        } else {
            session()->flash('error', 'SPPD ID atau SPT ID diperlukan untuk membuat kwitansi');
            $this->redirect(route('documents'));
            return;
        }
    }

    public function loadAvailableSppds()
    {
        $this->spt = \App\Models\Spt::with(['notaDinas.participants.user', 'sppds'])->findOrFail($this->spt_id);
        
        // Get SPPDs that don't have receipts yet
        $this->availableSppds = $this->spt->sppds()
            ->whereDoesntHave('receipts')
            ->with(['spt.notaDinas.participants.user'])
            ->get();
        
        if ($this->availableSppds->isEmpty()) {
            session()->flash('error', 'Tidak ada SPPD yang tersedia untuk dibuatkan kwitansi');
            $this->redirect(route('documents'));
            return;
        }
        
        // Set default receipt date
        $this->receipt_date = now()->format('Y-m-d');
        
        // Load users for approval and treasurer
        $this->loadUsers();
    }

    public function loadSppdData()
    {
        $this->sppd = Sppd::with([
            'spt.notaDinas.participants.user',
            'spt.notaDinas.requestingUnit',
            'spt.notaDinas.fromUser.position',
            'spt.notaDinas.toUser.position',
            'spt.notaDinas.destinationCity.province',
            'spt.signedByUser.position',
            'signedByUser.position',
            'pptkUser.position'
        ])->findOrFail($this->sppd_id);
        
        // Set default receipt date
        $this->receipt_date = now()->format('Y-m-d');
        
        // Load users for approval and treasurer
        $this->loadUsers();
    }

    public function loadUsers()
    {
        // Treasurer users are now loaded directly in the view using searchableSelect
        $this->treasurerUsers = collect(); // Empty collection since we load directly in view
    }

    public function selectSppd($sppdId)
    {
        $this->sppd_id = $sppdId;
        $this->loadSppdData();
    }

    public function save()
    {
        $this->validate();

        // Prevent duplicate receipt for the same SPPD
        $existingReceipt = Receipt::where('sppd_id', $this->sppd_id)->first();
        if ($existingReceipt) {
            session()->flash('error', 'Kwitansi untuk SPPD ini sudah ada.');
            return;
        }

        // Generate document number
        $docNumberResult = DocumentNumberService::generate('KWT', Auth::user()->unit_id);

        // Get the first participant from Nota Dinas as payee
        $firstParticipant = $this->sppd->spt->notaDinas->participants->first();
        if (!$firstParticipant) {
            session()->flash('error', 'Tidak ada peserta dalam Nota Dinas');
            return;
        }

        // Create receipt
        $receipt = Receipt::create([
            'doc_no' => $docNumberResult['doc_no'],
            'number_is_manual' => $docNumberResult['is_manual'],
            'number_manual_reason' => $docNumberResult['manual_reason'],
            'number_format_id' => $docNumberResult['format_id'],
            'number_sequence_id' => $docNumberResult['sequence_id'],
            'number_scope_unit_id' => Auth::user()->unit_id,
            'sppd_id' => $this->sppd_id,
            'travel_grade_id' => $firstParticipant->user->travel_grade_id,
            'receipt_no' => $this->receipt_no ?: null,
            'receipt_date' => $this->receipt_date,
            'payee_user_id' => $firstParticipant->user_id,
            'account_code' => $this->account_code,
            'treasurer_user_id' => $this->treasurer_user_id,
            'treasurer_title' => $this->treasurer_title,
            'total_amount' => 0, // Will be calculated later
            'status' => 'DRAFT',
        ]);

        // Create treasurer snapshot
        $receipt->createTreasurerUserSnapshot();

        session()->flash('message', 'Kwitansi berhasil dibuat.');

        // Redirect back to documents page
        $this->redirect($this->getBackUrl());
    }

    public function getBackUrl()
    {
        if ($this->sppd) {
            $notaDinasId = $this->sppd->spt->nota_dinas_id;
            $sptId = $this->sppd->spt_id;
            $sppdId = $this->sppd_id;
            
            return route('documents', [
                'nota_dinas_id' => $notaDinasId,
                'spt_id' => $sptId,
                'sppd_id' => $sppdId
            ]);
        } elseif ($this->spt) {
            $notaDinasId = $this->spt->nota_dinas_id;
            $sptId = $this->spt_id;
            
            return route('documents', [
                'nota_dinas_id' => $notaDinasId,
                'spt_id' => $sptId
            ]);
        }
        
        return route('documents');
    }

    public function render()
    {
        return view('livewire.receipts.create');
    }
}
