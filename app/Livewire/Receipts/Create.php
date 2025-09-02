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
    public $payee_user_id = '';

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
    public $availableParticipants = [];
    
    // Available SPPDs for selection
    public $availableSppds = [];

    // Perhitungan biaya properties
    public $perdiemLines = [];
    public $transportLines = [];
    public $lodgingLines = [];
    public $representationLines = [];
    public $otherLines = [];
    public $totalAmount = 0;

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
        
        // Get all SPPDs for this SPT
        $allSppds = $this->spt->sppds()->with(['spt.notaDinas.participants.user'])->get();
        
        // Filter SPPDs that have participants without receipts
        $this->availableSppds = $allSppds->filter(function ($sppd) {
            // Get all participants from Nota Dinas
            $allParticipants = $sppd->spt->notaDinas->participants;
            
            // Get participants who already have receipts for this SPPD
            $participantsWithReceipts = Receipt::where('sppd_id', $sppd->id)
                ->pluck('payee_user_id')
                ->toArray();
            
            // Check if there are participants without receipts
            $availableParticipants = $allParticipants->filter(function ($participant) use ($participantsWithReceipts) {
                return !in_array($participant->user_id, $participantsWithReceipts);
            });
            
            return $availableParticipants->count() > 0;
        });
        
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
        
        // Load available participants (those who don't have receipts yet)
        $this->loadAvailableParticipants();
        
        // Check if there are available participants for this SPPD
        if ($this->availableParticipants->isEmpty()) {
            session()->flash('error', 'SPPD ini sudah memiliki kwitansi untuk semua peserta');
            $this->redirect(route('documents'));
            return;
        }
        
        // Auto-fill fields from existing receipt if available
        $this->autoFillFromExistingReceipt();
        
        // Load users for approval and treasurer
        $this->loadUsers();
    }

    public function loadAvailableParticipants()
    {
        if (!$this->sppd || !$this->sppd->spt || !$this->sppd->spt->notaDinas) {
            $this->availableParticipants = collect();
            return;
        }

        // Get all participants from Nota Dinas
        $allParticipants = $this->sppd->spt->notaDinas->participants;

        // Get participants who already have receipts for this SPPD
        $participantsWithReceipts = Receipt::where('sppd_id', $this->sppd_id)
            ->pluck('payee_user_id')
            ->toArray();

        // Filter out participants who already have receipts and sort them
        $this->availableParticipants = $allParticipants->filter(function ($participant) use ($participantsWithReceipts) {
            return !in_array($participant->user_id, $participantsWithReceipts);
        })->sort(function ($a, $b) {
            // 1. Sort by eselon (position_echelon_id) - lower number = higher eselon
            $ea = $a->user_position_echelon_id_snapshot ?? $a->user?->position?->echelon?->id ?? 999999;
            $eb = $b->user_position_echelon_id_snapshot ?? $b->user?->position?->echelon?->id ?? 999999;
            if ($ea !== $eb) return $ea <=> $eb;
            
            // 2. Sort by rank (rank_id) - higher number = higher rank
            $ra = $a->user_rank_id_snapshot ?? $a->user?->rank?->id ?? 0;
            $rb = $b->user_rank_id_snapshot ?? $b->user?->rank?->id ?? 0;
            if ($ra !== $rb) return $rb <=> $ra; // DESC order for rank
            
            // 3. Sort by NIP (alphabetical)
            $na = (string)($a->user_nip_snapshot ?? $a->user?->nip ?? '');
            $nb = (string)($b->user_nip_snapshot ?? $b->user?->nip ?? '');
            return strcmp($na, $nb);
        })->values();

        // Set default payee if available
        if ($this->availableParticipants->count() > 0 && empty($this->payee_user_id)) {
            $firstParticipant = $this->availableParticipants->first();
            $this->payee_user_id = $firstParticipant->user_id;
            
            // Set default travel grade from snapshot or user data
            $this->travel_grade_id = $firstParticipant->user_travel_grade_id_snapshot ?? 
                                   $firstParticipant->user?->travel_grade_id ?? '';
        }
    }

    public function loadUsers()
    {
        // Treasurer users are now loaded directly in the view using searchableSelect
        $this->treasurerUsers = collect(); // Empty collection since we load directly in view
    }

    public function autoFillFromExistingReceipt()
    {
        // Get the first existing receipt for this SPPD to auto-fill common fields
        $existingReceipt = Receipt::where('sppd_id', $this->sppd_id)->first();
        
        if ($existingReceipt) {
            // Auto-fill common fields that are usually the same for receipts in the same SPPD
            $this->account_code = $existingReceipt->account_code ?? $this->account_code;
            $this->treasurer_user_id = $existingReceipt->treasurer_user_id ?? $this->treasurer_user_id;
            $this->treasurer_title = $existingReceipt->treasurer_title ?? $this->treasurer_title;
            $this->receipt_date = $existingReceipt->receipt_date ?? $this->receipt_date;
            $this->travel_grade_id = $existingReceipt->travel_grade_id ?? $this->travel_grade_id;
        }
    }

    public function updatedPayeeUserId($value)
    {
        if ($value && $this->sppd && $this->sppd->spt && $this->sppd->spt->notaDinas) {
            // Find the selected participant
            $selectedParticipant = $this->sppd->spt->notaDinas->participants
                ->where('user_id', $value)
                ->first();
            
            if ($selectedParticipant) {
                // Update travel grade from snapshot or user data
                $this->travel_grade_id = $selectedParticipant->user_travel_grade_id_snapshot ?? 
                                       $selectedParticipant->user?->travel_grade_id ?? '';
            }
        }
    }

    public function selectSppd($sppdId)
    {
        $this->sppd_id = $sppdId;
        $this->loadSppdData();
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

    public function save()
    {
        $this->validate();

        // Prevent duplicate receipt for the same participant in the same SPPD
        $existingReceipt = Receipt::where('sppd_id', $this->sppd_id)
            ->where('payee_user_id', $this->payee_user_id)
            ->first();
        if ($existingReceipt) {
            session()->flash('error', 'Kwitansi untuk peserta ini sudah ada.');
            return;
        }

        // Get the selected participant
        $selectedParticipant = $this->sppd->spt->notaDinas->participants
            ->where('user_id', $this->payee_user_id)
            ->first();
        if (!$selectedParticipant) {
            session()->flash('error', 'Peserta yang dipilih tidak ditemukan dalam Nota Dinas');
            return;
        }

        // Calculate total amount
        $this->calculateTotal();

        // Create receipt with manual numbering (no automatic numbering for receipts)
        $receipt = Receipt::create([
            'doc_no' => null, // Manual numbering for receipts
            'number_is_manual' => true,
            'number_manual_reason' => 'Kwitansi menggunakan penomoran manual',
            'number_format_id' => null,
            'number_sequence_id' => null,
            'number_scope_unit_id' => null, // Not needed for manual numbering
            'sppd_id' => $this->sppd_id,
            'travel_grade_id' => $this->travel_grade_id, // Use travel grade from form
            'receipt_no' => $this->receipt_no ?: null,
            'receipt_date' => $this->receipt_date,
            'payee_user_id' => $selectedParticipant->user_id,
            'account_code' => $this->account_code,
            'treasurer_user_id' => $this->treasurer_user_id,
            'treasurer_title' => $this->treasurer_title,
            'total_amount' => $this->totalAmount,
            'status' => 'DRAFT',
        ]);

        // Create treasurer snapshot
        $receipt->createTreasurerUserSnapshot();

        // Create receipt lines
        $this->createReceiptLines($receipt);

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
