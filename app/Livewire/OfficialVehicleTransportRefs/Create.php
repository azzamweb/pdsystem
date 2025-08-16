<?php

namespace App\Livewire\OfficialVehicleTransportRefs;

use App\Models\OfficialVehicleTransportRef;
use App\Models\OrgPlace;
use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    #[Rule('required|exists:org_places,id')]
    public $origin_place_id = '';

    #[Rule('required|exists:districts,id')]
    public $destination_district_id = '';

    #[Rule('required|numeric|min:0')]
    public $pp_amount = '';

    #[Rule('required|string|in:Kedudukan Bengkalis,Kedudukan Duri')]
    public $context = '';

    public function save()
    {
        $this->validate();

        // Cek apakah kombinasi sudah ada
        $existing = OfficialVehicleTransportRef::where('origin_place_id', $this->origin_place_id)
            ->where('destination_district_id', $this->destination_district_id)
            ->where('context', $this->context)
            ->first();

        if ($existing) {
            $this->addError('context', 'Referensi untuk kombinasi tempat kerja, kecamatan, dan kedudukan ini sudah ada.');
            return;
        }

        OfficialVehicleTransportRef::create([
            'origin_place_id' => $this->origin_place_id,
            'destination_district_id' => $this->destination_district_id,
            'pp_amount' => $this->pp_amount,
            'context' => $this->context,
        ]);

        session()->flash('message', 'Referensi transportasi kendaraan dinas berhasil ditambahkan');
        return $this->redirect(route('official-vehicle-transport-refs.index'));
    }

    public function render()
    {
        $orgPlaces = OrgPlace::with('city.province')
            ->whereHas('city.province') // Pastikan hanya org places yang memiliki city dan province
            ->orderBy('name')
            ->get();
        $districts = District::with('city.province')
            ->whereHas('city.province') // Pastikan hanya districts yang memiliki city dan province
            ->orderBy('name')
            ->get();

        return view('livewire.official-vehicle-transport-refs.create', [
            'orgPlaces' => $orgPlaces,
            'districts' => $districts,
        ]);
    }
}
