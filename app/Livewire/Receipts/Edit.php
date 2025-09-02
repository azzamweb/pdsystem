<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use App\Models\User;
use App\Services\ReferenceRateService;
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

    // Transport rate tracking properties
    public $transportRateOverrides = [];

    // Validation properties for excessive values
    public $hasExcessiveValues = false;
    public $excessiveValueDetails = [];

    // Computed properties for reference rates
    public $airfareRate = null;
    public $airfareOrigin = '';
    public $airfareDestination = '';
    public $lodgingCap = null;
    public $lodgingProvince = '';
    public $perdiemDailyRate = null;
    public $perdiemTotalAmount = null;
    public $perdiemProvince = '';
    public $perdiemTripType = '';
    public $representationRate = null;
    public $representationTripType = '';
    public $transportIntraProvince = null;
    public $transportIntraDistrict = null;

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
        
        // Load reference rates if travel grade is selected
        if ($this->travel_grade_id) {
            $this->loadReferenceRates();
        }
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
                    'rate_info' => '',
                    'has_reference' => false,
                    'original_reference_rate' => 0,
                    'is_overridden' => false,
                    'exceeds_reference' => false,
                    'excess_amount' => 0,
                    'excess_percentage' => 0,
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

    private function loadReferenceRates()
    {
        if (!$this->travel_grade_id || !$this->receipt->sppd->spt->notaDinas) {
            return;
        }

        $referenceRateService = new ReferenceRateService();
        $notaDinas = $this->receipt->sppd->spt->notaDinas;
        
        // Load airfare rate
        $this->airfareRate = $referenceRateService->getAirfareRate(
            $notaDinas->origin_city_id ?? 1, // Default to Pekanbaru
            $notaDinas->destination_city_id
        );
        
        // Get city names for display
        if ($this->airfareRate) {
            $originCity = \App\Models\City::find($notaDinas->origin_city_id ?? 1);
            $destinationCity = \App\Models\City::find($notaDinas->destination_city_id);
            $this->airfareOrigin = $originCity ? $originCity->name : 'Pekanbaru';
            $this->airfareDestination = $destinationCity ? $destinationCity->name : 'N/A';
        }

        // Load lodging cap
        $this->lodgingCap = $referenceRateService->getLodgingCap(
            $notaDinas->destination_city->province_id ?? null,
            $this->travel_grade_id
        );
        
        if ($this->lodgingCap) {
            $destinationCity = \App\Models\City::find($notaDinas->destination_city_id);
            $this->lodgingProvince = $destinationCity ? $destinationCity->province->name : 'N/A';
        }

        // Load perdiem rate
        $tripType = $referenceRateService->getTripType($notaDinas);
        $this->perdiemDailyRate = $referenceRateService->getPerdiemRate(
            $notaDinas->destination_city->province_id ?? null,
            $this->travel_grade_id,
            $tripType
        );
        
        if ($this->perdiemDailyRate) {
            $destinationCity = \App\Models\City::find($notaDinas->destination_city_id);
            $this->perdiemProvince = $destinationCity ? $destinationCity->province->name : 'N/A';
            $this->perdiemTripType = $tripType;
            
            // Calculate total perdiem amount
            $tripDays = $this->calculateTripDays($notaDinas);
            $this->perdiemTotalAmount = $this->perdiemDailyRate * $tripDays;
        }

        // Load representation rate
        $this->representationRate = $referenceRateService->getRepresentationRate(
            $this->travel_grade_id,
            $tripType
        );
        
        if ($this->representationRate) {
            $this->representationTripType = $tripType;
        }

        // Load transport rates
        $this->transportIntraProvince = $referenceRateService->getIntraProvinceTransportRate(
            $notaDinas->origin_place_id ?? 1, // Default to main office
            $notaDinas->destination_city_id
        );
        
        $this->transportIntraDistrict = $referenceRateService->getIntraDistrictTransportRate(
            $notaDinas->origin_place_id ?? 1, // Default to main office
            $notaDinas->destination_district_id ?? null
        );
    }

    private function calculateTripDays($notaDinas)
    {
        if (!$notaDinas->start_date || !$notaDinas->trip_type) {
            return 0;
        }
        
        $startDate = \Carbon\Carbon::parse($notaDinas->start_date);
        $endDate = \Carbon\Carbon::parse($notaDinas->end_date);
        
        return $startDate->diffInDays($endDate) + 1;
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
            'rate_info' => '',
            'has_reference' => false,
            'original_reference_rate' => 0,
            'is_overridden' => false,
            'exceeds_reference' => false,
            'excess_amount' => 0,
            'excess_percentage' => 0,
        ];
    }

    public function removeTransportLine($index)
    {
        unset($this->transportLines[$index]);
        $this->transportLines = array_values($this->transportLines);
        $this->calculateTotal();
    }

    public function overrideTransportRate($index)
    {
        if (isset($this->transportLines[$index])) {
            // Simpan nilai referensi asli sebelum di-override
            if (!isset($this->transportLines[$index]['original_reference_rate']) || $this->transportLines[$index]['original_reference_rate'] == 0) {
                $this->transportLines[$index]['original_reference_rate'] = $this->transportLines[$index]['unit_amount'];
            }
            
            $this->transportLines[$index]['has_reference'] = false;
            $this->transportLines[$index]['rate_info'] = 'Nilai diubah manual oleh user';
            $this->transportLines[$index]['is_overridden'] = true;
            
            // Reset status excessive
            $this->transportLines[$index]['exceeds_reference'] = false;
            $this->transportLines[$index]['excess_amount'] = 0;
            $this->transportLines[$index]['excess_percentage'] = 0;
        }
    }

    private function autoFillTransportRate($index, $component)
    {
        if (!$component || !$this->receipt->sppd || !$this->receipt->sppd->spt->notaDinas) {
            return;
        }

        $referenceRateService = new ReferenceRateService();
        $notaDinas = $this->receipt->sppd->spt->notaDinas;
        $destinationCity = $notaDinas->destinationCity;
        $originPlace = $notaDinas->originPlace;
        $defaultOriginCity = $referenceRateService->getDefaultOriginCity();

        $unitAmount = null;
        $rateInfo = '';

        switch ($component) {
            case 'AIRFARE':
                $unitAmount = $defaultOriginCity ? $referenceRateService->getAirfareRate(
                    $defaultOriginCity->id, 
                    $destinationCity->id
                ) : null;
                $rateInfo = $defaultOriginCity ? "Tiket Pesawat: {$defaultOriginCity->name} → {$destinationCity->name}" : '';
                break;

            case 'INTRA_PROV':
                $unitAmount = $originPlace ? $referenceRateService->getIntraProvinceTransportRate(
                    $originPlace->id, 
                    $destinationCity->id
                ) : null;
                $rateInfo = $originPlace ? "Transport Dalam Provinsi: {$originPlace->name} → {$destinationCity->name}" : '';
                break;

            case 'INTRA_DISTRICT':
                $unitAmount = $originPlace && $destinationCity->district_id ? $referenceRateService->getIntraDistrictTransportRate(
                    $originPlace->id, 
                    $destinationCity->district_id
                ) : null;
                $rateInfo = $originPlace && $destinationCity->district_id ? "Transport Dalam Kabupaten: {$originPlace->name} → {$destinationCity->district_id}" : '';
                break;

            case 'OFFICIAL_VEHICLE':
                $unitAmount = 0;
                $rateInfo = "Kendaraan Dinas - Input manual sesuai ketentuan";
                break;

            case 'TAXI':
                $unitAmount = 0;
                $rateInfo = "Taxi - Input manual sesuai ketentuan";
                break;

            case 'RORO':
                $unitAmount = 0;
                $rateInfo = "Kapal RORO - Input manual sesuai ketentuan";
                break;

            case 'TOLL':
                $unitAmount = 0;
                $rateInfo = "Tol - Input manual sesuai ketentuan";
                break;

            case 'PARKIR_INAP':
                $unitAmount = 0;
                $rateInfo = "Parkir & Penginapan - Input manual sesuai ketentuan";
                break;
        }

        // Update the transport line with auto-filled data
        if (isset($this->transportLines[$index])) {
            // Jangan override nilai manual yang sudah ada
            if (!$this->transportLines[$index]['is_overridden']) {
                $this->transportLines[$index]['unit_amount'] = $unitAmount ?? 0;
            }
            
            $this->transportLines[$index]['rate_info'] = $rateInfo;
            $this->transportLines[$index]['has_reference'] = $unitAmount !== null;
            $this->transportLines[$index]['original_reference_rate'] = $unitAmount ?? 0;
            
            // Jika ini auto-fill baru, reset status override
            if (!$this->transportLines[$index]['is_overridden']) {
                $this->transportLines[$index]['is_overridden'] = false;
                $this->transportLines[$index]['exceeds_reference'] = false;
                $this->transportLines[$index]['excess_amount'] = 0;
                $this->transportLines[$index]['excess_percentage'] = 0;
            }
        }
    }

    public function checkManualValueExceedsReference($index)
    {
        if (!isset($this->transportLines[$index]) || !$this->transportLines[$index]['is_overridden']) {
            return false;
        }

        $line = $this->transportLines[$index];
        $manualValue = $line['unit_amount'];
        $referenceValue = $line['original_reference_rate'] ?? 0;

        if ($manualValue > $referenceValue && $referenceValue > 0) {
            $this->transportLines[$index]['exceeds_reference'] = true;
            $this->transportLines[$index]['excess_amount'] = $manualValue - $referenceValue;
            $this->transportLines[$index]['excess_percentage'] = round((($manualValue - $referenceValue) / $referenceValue) * 100, 1);
        } else {
            $this->transportLines[$index]['exceeds_reference'] = false;
            $this->transportLines[$index]['excess_amount'] = 0;
            $this->transportLines[$index]['excess_percentage'] = 0;
        }

        return $this->transportLines[$index]['exceeds_reference'];
    }

    public function checkAllExcessiveValues()
    {
        $this->hasExcessiveValues = false;
        $this->excessiveValueDetails = [];

        // Check transport lines
        foreach ($this->transportLines as $index => $line) {
            if ($line['exceeds_reference']) {
                $this->hasExcessiveValues = true;
                $this->excessiveValueDetails[] = [
                    'type' => 'Transport',
                    'component' => $line['component'],
                    'index' => $index,
                    'manual_value' => $line['unit_amount'],
                    'reference_value' => $line['original_reference_rate'],
                    'excess_amount' => $line['excess_amount'],
                    'excess_percentage' => $line['excess_percentage']
                ];
            }
        }

        // Check lodging lines
        if ($this->lodgingCap) {
            foreach ($this->lodgingLines as $index => $line) {
                if (($line['unit_amount'] ?? 0) > $this->lodgingCap) {
                    $this->hasExcessiveValues = true;
                    $this->excessiveValueDetails[] = [
                        'type' => 'Penginapan',
                        'component' => 'LODGING',
                        'index' => $index,
                        'manual_value' => $line['unit_amount'],
                        'reference_value' => $this->lodgingCap,
                        'excess_amount' => $line['unit_amount'] - $this->lodgingCap,
                        'excess_percentage' => round((($line['unit_amount'] - $this->lodgingCap) / $this->lodgingCap) * 100, 1)
                    ];
                }
            }
        }

        // Check perdiem lines
        if ($this->perdiemDailyRate) {
            foreach ($this->perdiemLines as $index => $line) {
                if (($line['unit_amount'] ?? 0) > $this->perdiemDailyRate) {
                    $this->hasExcessiveValues = true;
                    $this->excessiveValueDetails[] = [
                        'type' => 'Uang Harian',
                        'component' => 'PERDIEM',
                        'index' => $index,
                        'manual_value' => $line['unit_amount'],
                        'reference_value' => $this->perdiemDailyRate,
                        'excess_amount' => $line['unit_amount'] - $this->perdiemDailyRate,
                        'excess_percentage' => round((($line['unit_amount'] - $this->perdiemDailyRate) / $this->perdiemDailyRate) * 100, 1)
                    ];
                }
            }
        }

        // Check representation lines
        if ($this->representationRate) {
            foreach ($this->representationLines as $index => $line) {
                if (($line['unit_amount'] ?? 0) > $this->representationRate) {
                    $this->hasExcessiveValues = true;
                    $this->excessiveValueDetails[] = [
                        'type' => 'Representatif',
                        'component' => 'REPRESENTATION',
                        'index' => $index,
                        'manual_value' => $line['unit_amount'],
                        'reference_value' => $this->representationRate,
                        'excess_amount' => $line['unit_amount'] - $this->representationRate,
                        'excess_percentage' => round((($line['unit_amount'] - $this->representationRate) / $this->representationRate) * 100, 1)
                    ];
                }
            }
        }

        return $this->hasExcessiveValues;
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

    public function updatedTravelGradeId($value)
    {
        if ($value) {
            $this->loadReferenceRates();
        }
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
        // Check if any transport component has changed and auto-fill rates
        foreach ($this->transportLines as $index => $line) {
            if (isset($line['component']) && !empty($line['component'])) {
                $this->autoFillTransportRate($index, $line['component']);
            }
        }
        
        // Check if any manual values exceed reference rates
        foreach ($this->transportLines as $index => $line) {
            if ($line['is_overridden']) {
                $this->checkManualValueExceedsReference($index);
            }
        }
        
        // Check all excessive values for warning banner
        $this->checkAllExcessiveValues();
        
        $this->calculateTotal();
    }

    // Method untuk handle perubahan nilai manual pada transport lines
    public function updatedTransportLinesUnitAmount($value, $key)
    {
        // Jika nilai berubah dan ini adalah transport yang sudah di-override, check excessive
        $index = explode('.', $key)[1];
        if (isset($this->transportLines[$index]) && $this->transportLines[$index]['is_overridden']) {
            $this->checkManualValueExceedsReference($index);
            $this->checkAllExcessiveValues();
        }
        
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

        // Check for excessive values before updating
        if ($this->checkAllExcessiveValues()) {
            session()->flash('error', 'Terdapat nilai yang melebihi standar referensi. Silakan sesuaikan terlebih dahulu sebelum menyimpan.');
            return;
        }

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
