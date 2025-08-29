<?php

namespace App\Livewire\Sppd;

use App\Models\Sppd;
use App\Models\Spt;
use App\Models\User;
use App\Models\OrgPlace;
use App\Models\City;
use App\Models\TransportMode;
use App\Models\DocNumberFormat;
use App\Services\DocumentNumberService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $spt_id = null;
    public $spt = null;

    #[Rule('required|date')]
    public $sppd_date = '';

    // Bidang yang akan diterapkan ke semua SPPD yang dibuat dari SPT ini
    // Dapat memilih lebih dari satu moda transportasi
    public $transport_mode_ids = [];
    // Origin place now refers to Nota Dinas' origin place via SPT → Nota Dinas
    public $origin_place_id = '';
    // Kota tujuan tidak ditampilkan di form; diisi otomatis dari Nota Dinas
    public $destination_city_id = '';

    // Gunakan tanggal dari Nota Dinas (jika ada) untuk hari
    // Tanggal berangkat/kembali tidak ditampilkan di form; diisi otomatis dari Nota Dinas
    #[Rule('required|integer|min:1')]
    public $days_count = 1;

    // Sumber dana tidak ditampilkan di form (opsional)
    public $funding_source = '';

    // Peserta yang akan dibuatkan SPPD
    #[Rule('required|array|min:1')]
    public $selected_user_ids = [];

    // Penandatangan
    #[Rule('required|exists:users,id')]
    public $signed_by_user_id = '';
    #[Rule('nullable|string')]
    public $assignment_title = '';
    public $use_custom_assignment_title = false;

    // Bantuan UI
    public $participants = [];
    public $format_string = null;
    public $format_example = null;

    public function mount($spt_id = null): void
    {
        $this->spt_id = $spt_id ?? request()->query('spt_id');
        if (!$this->spt_id) {
            session()->flash('error', 'SPT tidak ditemukan untuk pembuatan SPPD.');
            $this->redirect(route('spt.index'));
            return;
        }

        $this->spt = Spt::with([
            'notaDinas.participants.user', 
            'notaDinas.destinationCity.province', 
            'notaDinas.requestingUnit',
            'notaDinas.fromUser.position',
            'notaDinas.toUser.position',
            'signedByUser.position',
            'sppds'
        ])->findOrFail($this->spt_id);

        // Prefill nilai umum
        $this->sppd_date = $this->spt->spt_date ?: now()->format('Y-m-d');

        $this->destination_city_id = $this->spt->notaDinas?->destination_city_id ?: '';

        // Get participants who don't have SPPD yet
        $existingSppdUserIds = $this->spt->sppds->pluck('user_id')->toArray();
        
        $this->participants = $this->spt->notaDinas?->participants?->map(function ($p) {
            return [
                'id' => $p->user?->id,
                'name' => $p->user?->fullNameWithTitles() ?? $p->user?->name,
                'nip' => $p->user?->nip,
            ];
        })->filter(fn($x) => !empty($x['id']) && !in_array($x['id'], $existingSppdUserIds))->values()->all();
        
        $this->selected_user_ids = collect($this->participants)->pluck('id')->all();

        // Default penandatangan dari SPT
        $this->signed_by_user_id = $this->spt->signed_by_user_id ?? '';
        // Set assignment title default dari penandatangan jika ada
        $this->assignment_title = $this->guessAssignmentTitle();

        // Check if there are participants available for SPPD creation
        if (empty($this->participants)) {
            session()->flash('error', 'Semua peserta sudah memiliki SPPD. Tidak ada peserta baru yang dapat dibuatkan SPPD.');
            $this->redirect(route('documents'));
            return;
        }

        // Tampilkan format aktif SPPD untuk verifikasi cepat
        $unitScopeId = $this->spt->notaDinas?->requesting_unit_id;
        $fmt = DocNumberFormat::where('doc_type', 'SPPD')
            ->where(function($q) use ($unitScopeId) {
                $q->where('unit_scope_id', $unitScopeId)->orWhereNull('unit_scope_id');
            })
            ->where('is_active', true)
            ->orderByRaw('unit_scope_id is null')
            ->first();
        if ($fmt) {
            $this->format_string = $fmt->format_string;
            $date = $this->sppd_date ? \Carbon\Carbon::parse($this->sppd_date) : now();
            $unitCode = null;
            if ($unitScopeId) {
                $unit = \App\Models\Unit::find($unitScopeId);
                $unitCode = $unit ? ($unit->code ?? $unit->id) : $unitScopeId;
            }
            $replace = [
                '{seq}' => str_pad('1', $fmt->padding, '0', STR_PAD_LEFT),
                '{doc_code}' => $fmt->doc_code,
                '{unit_code}' => $unitCode,
                '{roman_month}' => \App\Services\DocumentNumberService::romanMonth($date->month),
                '{month}' => $date->format('m'),
                '{year}' => $date->format('Y'),
            ];
            $example = $fmt->format_string;
            foreach ($replace as $k => $v) { $example = str_replace($k, $v, $example); }
            $this->format_example = $example;
        }
    }

    public function toggleSelectAll(bool $checked): void
    {
        $this->selected_user_ids = $checked ? collect($this->participants)->pluck('id')->all() : [];
    }

    public function updatedSignedByUserId(): void
    {
        // Jika assignment_title kosong, isi otomatis saat penandatangan berubah
        if (!trim((string)$this->assignment_title)) {
            $this->assignment_title = $this->guessAssignmentTitle();
        }
    }

    public function updatedUseCustomAssignmentTitle($val): void
    {
        // Jika custom assignment title dimatikan, reset ke default
        if (!$val) {
            $this->assignment_title = $this->guessAssignmentTitle();
        }
    }

    private function guessAssignmentTitle(): string
    {
        if (!$this->signed_by_user_id) return '';
        $u = User::with(['position'])->find($this->signed_by_user_id);
        if (!$u) return '';
        return (string)($u->position_desc ?: ($u->position->name ?? ''));
    }

    public function save(): mixed
    {
        $this->validate([
            'sppd_date' => 'required|date',
            'trip_type' => 'required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT',
            'selected_user_ids' => 'required|array|min:1',
            'selected_user_ids.*' => 'exists:users,id',
            'transport_mode_ids' => 'required|array|min:1',
            'transport_mode_ids.*' => 'exists:transport_modes,id',
            'signed_by_user_id' => 'required|exists:users,id',
            'assignment_title' => 'nullable|string',
        ]);

        if (!$this->spt) {
            session()->flash('error', 'SPT tidak ditemukan.');
            return null;
        }

        $unitScopeId = $this->spt->notaDinas?->requesting_unit_id;
        if (!$unitScopeId) {
            session()->flash('error', 'Unit pada Nota Dinas tidak ditemukan. Pastikan field unit terisi.');
            return null;
        }

        // Wajib: tujuan harus ada dari ND (tidak ditampilkan di form)
        if (!$this->destination_city_id) {
            $this->destination_city_id = $this->spt->notaDinas?->destination_city_id;
        }
        if (!$this->destination_city_id) {
            session()->flash('error', 'Kota tujuan pada Nota Dinas tidak ditemukan.');
            return null;
        }



        $createdCount = 0;
        $failMessages = [];
        foreach ($this->selected_user_ids as $userId) {
            try {
                $gen = DocumentNumberService::generate('SPPD', $unitScopeId, $this->sppd_date ?: now(), [
                    'spt_id' => $this->spt->id,
                    'user_id' => $userId,
                ], auth()->id());

                // Atur assignment title berdasarkan mode
                $assignmentTitle = trim((string)$this->assignment_title);
                if (!$this->use_custom_assignment_title || $assignmentTitle === '') {
                    $assignmentTitle = $this->guessAssignmentTitle();
                }

                $sppd = Sppd::create([
                    'doc_no' => $gen['number'],
                    'number_is_manual' => false,
                    'number_manual_reason' => null,
                    'number_format_id' => isset($gen['format']) ? $gen['format']->id : null,
                    'number_sequence_id' => isset($gen['sequence']) ? $gen['sequence']->id : null,
                    'number_scope_unit_id' => $unitScopeId,
                    'sppd_date' => $this->sppd_date,
                    'spt_id' => $this->spt->id,
                    'user_id' => $userId,
                    'signed_by_user_id' => $this->signed_by_user_id,
                    'assignment_title' => $assignmentTitle,
                    'destination_city_id' => $this->destination_city_id,
                    'funding_source' => $this->funding_source,
                ]);

                // Attach transport modes
                if (is_array($this->transport_mode_ids) && count($this->transport_mode_ids) > 0) {
                    $sppd->transportModes()->attach($this->transport_mode_ids);
                }
                $createdCount++;
            } catch (\Throwable $e) {
                // lanjut ke user berikutnya, catat error minimal
                $failMessages[] = $e->getMessage();
            }
        }

        if ($createdCount > 0) {
            session()->flash('message', 'SPPD berhasil dibuat untuk '.$createdCount.' pegawai.');
            // Redirect ke halaman utama dengan state yang sama
            return $this->redirect(route('documents', [
                'nota_dinas_id' => $this->spt->nota_dinas_id,
                'spt_id' => $this->spt->id
            ]));
        }

        $msg = 'Gagal membuat SPPD: tidak ada dokumen yang berhasil dibuat.';
        if (!empty($failMessages)) {
            $msg .= ' Alasan pertama: ' . $failMessages[0];
        }
        session()->flash('error', $msg);
        return null;
    }

    public function render()
    {
        return view('livewire.sppd.create', [
            'transportModes' => TransportMode::orderBy('name')->get(),
            'orgPlaces' => OrgPlace::orderBy('name')->get(),
        ]);
    }
}
