<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public $receipt_id = null;
    public $receipt = null;

    #[Rule('nullable|string')]
    public $account_code = '';

    #[Rule('required|exists:users,id')]
    public $treasurer_user_id = '';

    #[Rule('required|in:Bendahara Pengeluaran,Bendahara Pengeluaran Pembantu')]
    public $treasurer_title = '';

    #[Rule('required|date')]
    public $receipt_date = '';

    public $receipt_no = '';

    #[Rule('required|exists:travel_grades,id')]
    public $travel_grade_id = '';

    // Available users for selection
    public $approvalUsers = [];
    public $treasurerUsers = [];

    public function mount($receipt_id = null): void
    {
        $this->receipt_id = $receipt_id ?? request()->route('receipt');
        
        if (!$this->receipt_id) {
            session()->flash('error', 'Kwitansi tidak ditemukan.');
            $this->redirect(route('documents'));
            return;
        }

        $this->receipt = Receipt::with(['sppd.spt.notaDinas.participants.user', 'payeeUser', 'treasurerUser'])->findOrFail($this->receipt_id);
        
        // Prefill values
        $this->account_code = $this->receipt->account_code;
        $this->treasurer_user_id = $this->receipt->treasurer_user_id;
        $this->treasurer_title = $this->receipt->treasurer_title;
        $this->receipt_date = $this->receipt->receipt_date ?: now()->format('Y-m-d');
        $this->receipt_no = $this->receipt->receipt_no;
        $this->travel_grade_id = $this->receipt->travel_grade_id;
        
        // Load users for treasurer (bendahara) - semua user untuk searchable select
        $this->treasurerUsers = User::with(['position', 'unit'])
            ->orderBy('name')
            ->get();
    }

    public function update()
    {
        $this->validate();

        // Store original values for comparison
        $originalTreasurerUserId = $this->receipt->treasurer_user_id;

        // Update receipt
        $this->receipt->update([
            'account_code' => $this->account_code,
            'travel_grade_id' => $this->travel_grade_id,
            'treasurer_user_id' => $this->treasurer_user_id,
            'treasurer_title' => $this->treasurer_title,
            'receipt_date' => $this->receipt_date,
            'receipt_no' => $this->receipt_no ?: null,
        ]);

        // Refresh the receipt model to get updated data
        $this->receipt->refresh();

        // Update treasurer snapshot if user changed or snapshot is missing
        if ($originalTreasurerUserId != $this->treasurer_user_id || !$this->receipt->treasurer_user_name_snapshot) {
            $this->receipt->createTreasurerUserSnapshot();
        }

        session()->flash('message', 'Kwitansi berhasil diperbarui.');

        // Redirect back to documents page
        $this->redirect($this->getBackUrl());
    }

    public function getBackUrl()
    {
        if ($this->receipt) {
            $notaDinasId = $this->receipt->sppd->spt->nota_dinas_id;
            $sptId = $this->receipt->sppd->spt_id;
            $sppdId = $this->receipt->sppd_id;
            
            return route('documents', [
                'nota_dinas_id' => $notaDinasId,
                'spt_id' => $sptId,
                'sppd_id' => $sppdId
            ]);
        }
        
        return route('documents');
    }

    public function render()
    {
        return view('livewire.receipts.edit');
    }
}
