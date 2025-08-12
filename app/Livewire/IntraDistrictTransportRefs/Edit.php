<?php

namespace App\Livewire\IntraDistrictTransportRefs;

use App\Models\IntraDistrictTransportRef;
use App\Models\OrgPlace;
use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public IntraDistrictTransportRef $transportRef;

    #[Rule('required|exists:org_places,id')]
    public $origin_place_id = '';

    #[Rule('required|exists:districts,id')]
    public $destination_district_id = '';

    #[Rule('required|numeric|min:0')]
    public $pp_amount = '';

    public function mount(IntraDistrictTransportRef $transportRef)
    {
        $this->transportRef = $transportRef;
        $this->origin_place_id = $this->transportRef->origin_place_id;
        $this->destination_district_id = $this->transportRef->destination_district_id;
        $this->pp_amount = $this->transportRef->pp_amount;
    }

    public function save()
    {
        $this->validate();

        // Check if combination already exists (excluding current record)
        $existing = IntraDistrictTransportRef::where('origin_place_id', $this->origin_place_id)
            ->where('destination_district_id', $this->destination_district_id)
            ->where('id', '!=', $this->transportRef->id)
            ->first();

        if ($existing) {
            $this->addError('destination_district_id', 'Referensi untuk kombinasi tempat kerja dan kecamatan ini sudah ada.');
            return;
        }

        $this->transportRef->update([
            'origin_place_id' => $this->origin_place_id,
            'destination_district_id' => $this->destination_district_id,
            'pp_amount' => $this->pp_amount,
        ]);

        session()->flash('message', 'Referensi transportasi dalam kecamatan berhasil diperbarui');
        return $this->redirect(route('intra-district-transport-refs.index'));
    }

    public function render()
    {
        $orgPlaces = OrgPlace::with(['city.province'])->orderBy('name')->get();
        $districts = District::with(['city.province'])->orderBy('name')->get();

        return view('livewire.intra-district-transport-refs.edit', [
            'orgPlaces' => $orgPlaces,
            'districts' => $districts,
        ]);
    }
}
