<?php

namespace App\Livewire\Sppd;

use App\Models\Sppd;
use App\Models\User;
use App\Models\OrgPlace;
use App\Models\City;
use App\Models\TransportMode;
use App\Models\Spt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public $sppd_id = null;
    public $sppd = null;

    #[Rule('required|date')]
    public $sppd_date = '';

    #[Rule('required|exists:org_places,id')]
    public $origin_place_id = '';

    #[Rule('required|exists:cities,id')]
    public $destination_city_id = '';

    #[Rule('required|array|min:1')]
    public $transport_mode_ids = [];

    #[Rule('required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT')]
    public $trip_type = 'LUAR_DAERAH';





    public $funding_source = '';



    public $user_name = '';
    public $spt_info = '';

    public function mount($sppd_id = null): void
    {
        $this->sppd_id = $sppd_id ?? request()->route('sppd');
        if (!$this->sppd_id) {
            session()->flash('error', 'SPPD tidak ditemukan.');
            $this->redirect(route('sppd.index'));
            return;
        }

        $this->sppd = Sppd::with(['user', 'spt.notaDinas', 'originPlace', 'destinationCity', 'transportModes'])
            ->findOrFail($this->sppd_id);

        // Load data SPPD ke form
        $this->sppd_date = $this->sppd->sppd_date ?: now()->format('Y-m-d');
        $this->origin_place_id = $this->sppd->origin_place_id;
        $this->destination_city_id = $this->sppd->destination_city_id;
        $this->transport_mode_ids = $this->sppd->transportModes->pluck('id')->toArray();
        $this->trip_type = $this->sppd->trip_type ?: 'LUAR_DAERAH';


        $this->funding_source = $this->sppd->funding_source ?: '';

        // Info tambahan untuk display
        $this->user_name = $this->sppd->user?->fullNameWithTitles() ?? 'N/A';
        $this->spt_info = $this->sppd->spt?->doc_no ?? 'N/A';
    }



    public function save(): mixed
    {
        $this->validate([
            'sppd_date' => 'required|date',
            'origin_place_id' => 'required|exists:org_places,id',
            'destination_city_id' => 'required|exists:cities,id',
            'transport_mode_ids' => 'required|array|min:1',
            'transport_mode_ids.*' => 'exists:transport_modes,id',
            'trip_type' => 'required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT',

        ]);

        if (!$this->sppd) {
            session()->flash('error', 'SPPD tidak ditemukan.');
            return null;
        }

        try {
            $this->sppd->update([
                'sppd_date' => $this->sppd_date,
                'origin_place_id' => $this->origin_place_id,
                'destination_city_id' => $this->destination_city_id,
                'trip_type' => $this->trip_type,
                'funding_source' => $this->funding_source,
            ]);

            // Sync transport modes
            $this->sppd->transportModes()->sync($this->transport_mode_ids);

            session()->flash('message', 'SPPD berhasil diperbarui.');
            // Redirect ke halaman utama dengan state yang sama
            return $this->redirect(route('documents', [
                'nota_dinas_id' => $this->sppd->spt->nota_dinas_id,
                'spt_id' => $this->sppd->spt_id,
                'sppd_id' => $this->sppd->id
            ]));
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui SPPD: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.sppd.edit', [
            'transportModes' => TransportMode::orderBy('name')->get(),
            'orgPlaces' => OrgPlace::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
        ]);
    }
}
