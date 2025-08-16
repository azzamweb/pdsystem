<?php

namespace App\Livewire\IntraProvinceTransportRefs;

use App\Models\IntraProvinceTransportRef;
use App\Models\OrgPlace;
use App\Models\City;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    #[Rule('required|exists:org_places,id')]
    public $origin_place_id = '';

    #[Rule('required|exists:cities,id')]
    public $destination_city_id = '';

    #[Rule('required|numeric|min:0')]
    public $pp_amount = '';

    public function save()
    {
        $this->validate();

        // Cek apakah kombinasi sudah ada
        $existing = IntraProvinceTransportRef::where('origin_place_id', $this->origin_place_id)
            ->where('destination_city_id', $this->destination_city_id)
            ->first();

        if ($existing) {
            $this->addError('destination_city_id', 'Referensi untuk kombinasi tempat kerja dan kota ini sudah ada.');
            return;
        }

        IntraProvinceTransportRef::create([
            'origin_place_id' => $this->origin_place_id,
            'destination_city_id' => $this->destination_city_id,
            'pp_amount' => $this->pp_amount,
        ]);

        session()->flash('message', 'Referensi transportasi dalam provinsi berhasil ditambahkan');
        return $this->redirect(route('intra-province-transport-refs.index'));
    }

    public function render()
    {
        $orgPlaces = OrgPlace::with('city.province')
            ->whereHas('city.province') // Pastikan hanya org places yang memiliki city dan province
            ->orderBy('name')
            ->get();
        $cities = City::with('province')
            ->whereHas('province') // Pastikan hanya cities yang memiliki province
            ->orderBy('name')
            ->get();

        return view('livewire.intra-province-transport-refs.create', [
            'orgPlaces' => $orgPlaces,
            'cities' => $cities,
        ]);
    }
}
