<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use App\Models\ReceiptLine;
use App\Models\Sppd;
use App\Models\TravelGrade;
use App\Models\PerdiemRate;
use App\Models\LodgingCap;
use App\Models\RepresentationRate;
use App\Models\IntraProvinceTransportRef;
use App\Models\IntraDistrictTransportRef;
use App\Models\OfficialVehicleTransportRef;
use App\Models\AirfareRef;
use App\Models\AtCostComponent;
use App\Models\DocNumberFormat;
use App\Services\DocumentNumberService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $sppd_id = null;
    public $sppd = null;

    #[Rule('required|date')]
    public $receipt_date = '';

    // Travel grade akan diambil otomatis dari user
    public $travel_grade_id = '';

    // Penerima pembayaran otomatis dari SPPD
    public $payee_user_id = '';

    public $notes = '';

    // Receipt lines data
    public $receipt_lines = [];

    // Available travel grades
    public $travel_grades = [];

    // Manual numbering for receipts (from SIPD)
    public $manual_doc_no = '';
    public $number_is_manual = false;

    // Simplified components data
    public $transport_laut = 0;
    public $transport_darat = 0;
    public $transport_darat_roro = 0;
    public $transport_udara = 0;
    public $transport_taxi = 0;
    public $lodging_nights = 0;
    public $is_no_lodging = false;
    public $lodging_rate = 0;
    public $perdiem_rate = 0;
    public $representasi_rate = 0;
    public $show_representasi = false;

    public function mount($sppd_id = null): void
    {
        $this->sppd_id = $sppd_id ?? request()->query('sppd_id');
        if (!$this->sppd_id) {
            session()->flash('error', 'SPPD tidak ditemukan untuk pembuatan kwitansi.');
            $this->redirect(route('documents'));
            return;
        }

        $this->sppd = Sppd::with([
            'user', 
            'spt.notaDinas.participants.user',
            'spt.notaDinas.originPlace',
            'spt.notaDinas.destinationCity.province',
            'transportModes'
        ])->findOrFail($this->sppd_id);

        // Prefill values
        $this->receipt_date = now()->format('Y-m-d');
        $this->payee_user_id = $this->sppd->user_id;
        
        // Debug: pastikan payee_user_id terisi
        if (!$this->payee_user_id) {
            session()->flash('error', 'Penerima pembayaran tidak ditemukan.');
            $this->redirect(route('documents'));
            return;
        }

        // Ambil travel grade otomatis dari user
        $userTravelGradeMap = $this->sppd->user->travelGradeMap;
        if (!$userTravelGradeMap) {
            session()->flash('error', 'Pegawai belum memiliki data tingkat perjalanan. Silakan update data pegawai terlebih dahulu.');
            $this->redirect(route('documents'));
            return;
        }
        
        $this->travel_grade_id = $userTravelGradeMap->travel_grade_id;
        
        // Load travel grades untuk referensi
        $this->travel_grades = TravelGrade::orderBy('name')->get();

        // Load rates based on SPPD data
        $this->loadRates();
        
        // Debug: log hasil perhitungan
        Log::info('Receipt Create - Initial calculation:', [
            'travel_grade_id' => $this->travel_grade_id,
            'show_representasi' => $this->show_representasi
        ]);
    }

    public function loadRates()
    {
        if (!$this->sppd || !$this->travel_grade_id) {
            return;
        }

        $travelGrade = TravelGrade::find($this->travel_grade_id);
        if (!$travelGrade) {
            return;
        }

        $destinationProvinceId = $this->sppd->spt?->notaDinas?->destinationCity?->province_id;

        // Load Perdiem Rate based on trip type
        $perdiemRate = PerdiemRate::where('province_id', $destinationProvinceId)
            ->first();
        
        if ($perdiemRate) {
            // Determine which rate to use based on trip type
            switch ($this->sppd->trip_type) {
                case 'LUAR_DAERAH':
                    $this->perdiem_rate = $perdiemRate->luar_kota;
                    break;
                case 'DALAM_DAERAH_GT8H':
                    $this->perdiem_rate = $perdiemRate->dalam_kota_gt8h;
                    break;
                case 'DIKLAT':
                    $this->perdiem_rate = $perdiemRate->diklat;
                    break;
                default:
                    $this->perdiem_rate = $perdiemRate->dalam_kota_gt8h; // Default untuk DALAM_DAERAH_LE8H
                    break;
            }
        } else {
            $this->perdiem_rate = 0;
        }

        // Load Lodging Rate
        $lodgingCap = LodgingCap::where('travel_grade_id', $travelGrade->id)
            ->where('province_id', $destinationProvinceId)
            ->first();
        $this->lodging_rate = $lodgingCap ? $lodgingCap->cap_amount : 0;

        // Check if user is eligible for representasi (Eselon II or equivalent)
        $userPosition = $this->sppd->user->position;
        if ($userPosition && $userPosition->echelon && $userPosition->echelon->level <= 2) {
            $this->show_representasi = true;
            
            // Load Representasi Rate based on trip type
            $representationRate = RepresentationRate::where('travel_grade_id', $travelGrade->id)->first();
            if ($representationRate) {
                // Determine which rate to use based on trip type
                if ($this->sppd->trip_type === 'LUAR_DAERAH') {
                    $this->representasi_rate = $representationRate->luar_kota;
                } else {
                    $this->representasi_rate = $representationRate->dalam_kota_gt8h;
                }
            } else {
                $this->representasi_rate = 0;
            }
        }
    }







    public function save()
    {
        $this->validate([
            'receipt_date' => 'required|date',
            'notes' => 'nullable|string',
            'manual_doc_no' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Kwitansi menggunakan penomoran manual (dari SIPD)
            $docNumber = null;
            $number_is_manual = false;
            $number_manual_reason = null;
            
            if ($this->manual_doc_no) {
                $docNumber = $this->manual_doc_no;
                $number_is_manual = true;
                $number_manual_reason = 'Nomor dari aplikasi SIPD';
            }

            // Create receipt
            $receipt = Receipt::create([
                'doc_no' => $docNumber,
                'number_is_manual' => $number_is_manual,
                'number_manual_reason' => $number_manual_reason,
                'sppd_id' => $this->sppd_id,
                'travel_grade_id' => $this->travel_grade_id,
                'receipt_date' => $this->receipt_date,
                'payee_user_id' => $this->payee_user_id,
                'notes' => $this->notes,
                'status' => 'DRAFT',
            ]);

            // Create receipt lines for transportation
            $this->createTransportationLines($receipt);
            
            // Create lodging line
            $this->createLodgingLine($receipt);
            
            // Create perdiem line
            $this->createPerdiemLine($receipt);
            
            // Create representasi line if eligible
            if ($this->show_representasi) {
                $this->createRepresentasiLine($receipt);
            }

            DB::commit();

            session()->flash('message', 'Kwitansi berhasil dibuat.');
            
            // Redirect back to documents page with selected state
            $notaDinasId = $this->sppd->spt->nota_dinas_id;
            $sptId = $this->sppd->spt_id;
            $sppdId = $this->sppd->id;
            
            return redirect()->route('documents', [
                'nota_dinas_id' => $notaDinasId,
                'spt_id' => $sptId,
                'sppd_id' => $sppdId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal membuat kwitansi: ' . $e->getMessage());
        }
    }

    private function createTransportationLines($receipt)
    {
        $components = [
            'transport_laut' => 'TRANSPORT_LAUT',
            'transport_darat' => 'TRANSPORT_DARAT',
            'transport_darat_roro' => 'TRANSPORT_DARAT_RORO',
            'transport_udara' => 'TRANSPORT_UDARA',
            'transport_taxi' => 'TRANSPORT_TAXI',
        ];

        foreach ($components as $property => $component) {
            if ($this->$property > 0) {
                ReceiptLine::create([
                    'receipt_id' => $receipt->id,
                    'component' => $component,
                    'qty' => 1,
                    'unit' => 'PP',
                    'unit_amount' => $this->$property,
                    'line_total' => $this->$property,
                    'remark' => "Transportasi: " . str_replace('_', ' ', $component),
                ]);
            }
        }
    }

    private function createLodgingLine($receipt)
    {
        if ($this->is_no_lodging) {
            // 30% dari maksimal tarif penginapan
            $lodgingRate = $this->lodging_rate ?: 0;
            $amount = $lodgingRate * 0.3;
            ReceiptLine::create([
                'receipt_id' => $receipt->id,
                'component' => 'LODGING',
                'qty' => 1,
                'unit' => 'Kali',
                'unit_amount' => $amount,
                'line_total' => $amount,
                'is_no_lodging' => true,
                'destination_city' => $this->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A',
                'remark' => "Tidak menginap (30% dari tarif maksimal)",
            ]);
        } elseif ($this->lodging_nights > 0) {
            $lodgingRate = $this->lodging_rate ?: 0;
            $amount = $lodgingRate * $this->lodging_nights;
            ReceiptLine::create([
                'receipt_id' => $receipt->id,
                'component' => 'LODGING',
                'qty' => $this->lodging_nights,
                'unit' => 'Malam',
                'unit_amount' => $lodgingRate,
                'line_total' => $amount,
                'is_no_lodging' => false,
                'destination_city' => $this->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A',
                'remark' => "Penginapan {$this->lodging_nights} malam",
            ]);
        }
    }

    private function createPerdiemLine($receipt)
    {
        $perdiemRate = $this->perdiem_rate ?: 0;
        if ($perdiemRate > 0) {
            $amount = $perdiemRate * $this->sppd->days_count;
            ReceiptLine::create([
                'receipt_id' => $receipt->id,
                'component' => 'PERDIEM',
                'qty' => $this->sppd->days_count,
                'unit' => 'Hari',
                'unit_amount' => $perdiemRate,
                'line_total' => $amount,
                'destination_city' => $this->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A',
                'remark' => "Uang harian " . ($this->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A') . " ({$this->sppd->days_count} hari)",
            ]);
        }
    }

    private function createRepresentasiLine($receipt)
    {
        $representasiRate = $this->representasi_rate ?: 0;
        if ($representasiRate > 0) {
            ReceiptLine::create([
                'receipt_id' => $receipt->id,
                'component' => 'REPRESENTASI',
                'qty' => 1,
                'unit' => 'Kali',
                'unit_amount' => $representasiRate,
                'line_total' => $representasiRate,
                'remark' => "Representasi untuk Eselon II",
            ]);
        }
    }

    public function getTotalAmount()
    {
        $total = 0;
        
        // Transportation
        $total += ($this->transport_laut ?: 0) + ($this->transport_darat ?: 0) + ($this->transport_darat_roro ?: 0) + 
                 ($this->transport_udara ?: 0) + ($this->transport_taxi ?: 0);
        
        // Lodging
        $lodgingRate = $this->lodging_rate ?: 0;
        if ($this->is_no_lodging) {
            $total += $lodgingRate * 0.3;
        } elseif ($this->lodging_nights > 0) {
            $total += $lodgingRate * $this->lodging_nights;
        }
        
        // Perdiem
        $perdiemRate = $this->perdiem_rate ?: 0;
        $total += $perdiemRate * $this->sppd->days_count;
        
        // Representasi
        if ($this->show_representasi) {
            $representasiRate = $this->representasi_rate ?: 0;
            $total += $representasiRate;
        }
        
        return $total;
    }

    public function getBackUrl()
    {
        if ($this->sppd) {
            $notaDinasId = $this->sppd->spt->nota_dinas_id;
            $sptId = $this->sppd->spt_id;
            $sppdId = $this->sppd->id;
            
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
        return view('livewire.receipts.create');
    }
}
