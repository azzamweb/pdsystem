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

    // Perhitungan biaya properties
    public $perdiemLines = [];
    public $transportLines = [];
    public $lodgingLines = [];
    public $representationLines = [];
    public $otherLines = [];
    public $totalAmount = 0;

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

        // Load existing receipt lines
        $this->loadReceiptLines();
    }

    private function loadReceiptLines()
    {
        $receiptLines = $this->receipt->lines()->get();
        
        foreach ($receiptLines as $line) {
            if ($line->component === 'PERDIEM') {
                $this->perdiemLines[] = [
                    'qty' => $line->qty,
                    'unit_amount' => $line->unit_amount,
                ];
            } elseif (in_array($line->component, ['AIRFARE', 'INTRA_PROV', 'INTRA_DISTRICT', 'OFFICIAL_VEHICLE', 'TAXI', 'RORO', 'TOLL', 'PARKIR_INAP'])) {
                $this->transportLines[] = [
                    'component' => $line->component,
                    'qty' => $line->qty,
                    'unit_amount' => $line->unit_amount,
                ];
            } elseif ($line->component === 'LODGING') {
                $this->lodgingLines[] = [
                    'qty' => $line->qty,
                    'unit_amount' => $line->unit_amount,
                ];
            } elseif ($line->component === 'REPRESENTATION') {
                $this->representationLines[] = [
                    'qty' => $line->qty,
                    'unit_amount' => $line->unit_amount,
                ];
            } elseif ($line->component === 'LAINNYA') {
                $this->otherLines[] = [
                    'remark' => $line->remark,
                    'qty' => $line->qty,
                    'unit_amount' => $line->unit_amount,
                ];
            }
        }
        
        $this->calculateTotal();
    }

    // Method untuk mengelola perhitungan biaya
    public function addPerdiemLine()
    {
        $this->perdiemLines[] = [
            'qty' => 1,
            'unit_amount' => 0,
        ];
    }

    public function removePerdiemLine($index)
    {
        unset($this->perdiemLines[$index]);
        $this->perdiemLines = array_values($this->perdiemLines);
        $this->calculateTotal();
    }

    public function addTransportLine()
    {
        $this->transportLines[] = [
            'component' => '',
            'qty' => 1,
            'unit_amount' => 0,
        ];
    }

    public function removeTransportLine($index)
    {
        unset($this->transportLines[$index]);
        $this->transportLines = array_values($this->transportLines);
        $this->calculateTotal();
    }

    public function addLodgingLine()
    {
        $this->lodgingLines[] = [
            'qty' => 1,
            'unit_amount' => 0,
        ];
    }

    public function removeLodgingLine($index)
    {
        unset($this->lodgingLines[$index]);
        $this->lodgingLines = array_values($this->lodgingLines);
        $this->calculateTotal();
    }

    public function addRepresentationLine()
    {
        $this->representationLines[] = [
            'qty' => 1,
            'unit_amount' => 0,
        ];
    }

    public function removeRepresentationLine($index)
    {
        unset($this->representationLines[$index]);
        $this->representationLines = array_values($this->representationLines);
        $this->calculateTotal();
    }

    public function addOtherLine()
    {
        $this->otherLines[] = [
            'remark' => '',
            'qty' => 1,
            'unit_amount' => 0,
        ];
    }

    public function removeOtherLine($index)
    {
        unset($this->otherLines[$index]);
        $this->otherLines = array_values($this->otherLines);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;

        // Hitung total transportasi
        foreach ($this->transportLines as $line) {
            $total += ($line['qty'] ?? 0) * ($line['unit_amount'] ?? 0);
        }

        // Hitung total penginapan
        foreach ($this->lodgingLines as $line) {
            $total += ($line['qty'] ?? 0) * ($line['unit_amount'] ?? 0);
        }

        // Hitung total uang harian
        foreach ($this->perdiemLines as $line) {
            $total += ($line['qty'] ?? 0) * ($line['unit_amount'] ?? 0);
        }

        // Hitung total representatif
        foreach ($this->representationLines as $line) {
            $total += ($line['qty'] ?? 0) * ($line['unit_amount'] ?? 0);
        }

        // Hitung total biaya lainnya
        foreach ($this->otherLines as $line) {
            $total += ($line['qty'] ?? 0) * ($line['unit_amount'] ?? 0);
        }

        $this->totalAmount = $total;
    }

    public function updatedPerdiemLines()
    {
        $this->calculateTotal();
    }

    public function updatedTransportLines()
    {
        $this->calculateTotal();
    }

    public function updatedLodgingLines()
    {
        $this->calculateTotal();
    }

    public function updatedRepresentationLines()
    {
        $this->calculateTotal();
    }

    public function updatedOtherLines()
    {
        $this->calculateTotal();
    }

    private function updateReceiptLines()
    {
        // Delete existing receipt lines
        $this->receipt->lines()->delete();

        // Create new receipt lines
        $this->createReceiptLines($this->receipt);
    }

    private function createReceiptLines($receipt)
    {
        // Create perdiem lines
        foreach ($this->perdiemLines as $line) {
            if (($line['qty'] ?? 0) > 0 && ($line['unit_amount'] ?? 0) > 0) {
                \App\Models\ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => 'PERDIEM',
                    'qty' => $line['qty'],
                    'unit' => 'Hari',
                    'unit_amount' => $line['unit_amount'],
                    'line_total' => $line['qty'] * $line['unit_amount'],
                ]);
            }
        }

        // Create transport lines
        foreach ($this->transportLines as $line) {
            if (($line['qty'] ?? 0) > 0 && ($line['unit_amount'] ?? 0) > 0 && !empty($line['component'])) {
                \App\Models\ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => $line['component'],
                    'qty' => $line['qty'],
                    'unit' => $this->getUnitForComponent($line['component']),
                    'unit_amount' => $line['unit_amount'],
                    'line_total' => $line['qty'] * $line['unit_amount'],
                ]);
            }
        }

        // Create lodging lines
        foreach ($this->lodgingLines as $line) {
            if (($line['qty'] ?? 0) > 0 && ($line['unit_amount'] ?? 0) > 0) {
                \App\Models\ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => 'LODGING',
                    'qty' => $line['qty'],
                    'unit' => 'Malam',
                    'unit_amount' => $line['unit_amount'],
                    'line_total' => $line['qty'] * $line['unit_amount'],
                ]);
            }
        }

        // Create representation lines
        foreach ($this->representationLines as $line) {
            if (($line['qty'] ?? 0) > 0 && ($line['unit_amount'] ?? 0) > 0) {
                \App\Models\ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => 'REPRESENTATION',
                    'qty' => $line['qty'],
                    'unit' => 'Unit',
                    'unit_amount' => $line['unit_amount'],
                    'line_total' => $line['qty'] * $line['unit_amount'],
                ]);
            }
        }

        // Create other lines
        foreach ($this->otherLines as $line) {
            if (($line['qty'] ?? 0) > 0 && ($line['unit_amount'] ?? 0) > 0 && !empty($line['remark'])) {
                \App\Models\ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => 'LAINNYA',
                    'qty' => $line['qty'],
                    'unit' => 'Unit',
                    'unit_amount' => $line['unit_amount'],
                    'line_total' => $line['qty'] * $line['unit_amount'],
                    'remark' => $line['remark'],
                ]);
            }
        }
    }

    private function getUnitForComponent($component)
    {
        $units = [
            'AIRFARE' => 'Tiket',
            'INTRA_PROV' => 'Trip',
            'INTRA_DISTRICT' => 'Trip',
            'OFFICIAL_VEHICLE' => 'Trip',
            'TAXI' => 'Trip',
            'RORO' => 'Tiket',
            'TOLL' => 'Trip',
            'PARKIR_INAP' => 'Unit',
        ];

        return $units[$component] ?? 'Unit';
    }

    public function update()
    {
        $this->validate();

        // Store original values for comparison
        $originalTreasurerUserId = $this->receipt->treasurer_user_id;

        // Calculate total amount
        $this->calculateTotal();

        // Update receipt
        $this->receipt->update([
            'account_code' => $this->account_code,
            'travel_grade_id' => $this->travel_grade_id,
            'treasurer_user_id' => $this->treasurer_user_id,
            'treasurer_title' => $this->treasurer_title,
            'receipt_date' => $this->receipt_date,
            'receipt_no' => $this->receipt_no ?: null,
            'total_amount' => $this->totalAmount,
        ]);

        // Refresh the receipt model to get updated data
        $this->receipt->refresh();

        // Update treasurer snapshot if user changed or snapshot is missing
        if ($originalTreasurerUserId != $this->treasurer_user_id || !$this->receipt->treasurer_user_name_snapshot) {
            $this->receipt->createTreasurerUserSnapshot();
        }

        // Update receipt lines
        $this->updateReceiptLines();

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
