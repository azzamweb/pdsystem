<?php

namespace App\Livewire\DistrictPerdiemRates;

use App\Models\DistrictPerdiemRate;
use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Edit extends Component
{
    public DistrictPerdiemRate $districtPerdiemRate;

    #[Rule('required|string|max:255')]
    public $org_place_name = '';

    #[Rule('required|exists:districts,id')]
    public $district_id = '';

    #[Rule('required|string|max:10')]
    public $unit = 'OH';

    #[Rule('required|numeric|min:0')]
    public $daily_rate = '';

    public $is_active = true;

    public function mount(DistrictPerdiemRate $districtPerdiemRate)
    {
        $this->districtPerdiemRate = $districtPerdiemRate;
        $this->org_place_name = $districtPerdiemRate->org_place_name;
        $this->district_id = $districtPerdiemRate->district_id;
        $this->unit = $districtPerdiemRate->unit;
        $this->daily_rate = $districtPerdiemRate->daily_rate;
        $this->is_active = $districtPerdiemRate->is_active;
    }

    public function update()
    {
        $this->validate();

        // Check for duplicate (excluding current record)
        $existing = DistrictPerdiemRate::where('org_place_name', $this->org_place_name)
            ->where('district_id', $this->district_id)
            ->where('id', '!=', $this->districtPerdiemRate->id)
            ->first();

        if ($existing) {
            $this->addError('org_place_name', 'Tarif untuk kedudukan dan kecamatan ini sudah ada.');
            return;
        }

        $this->districtPerdiemRate->update([
            'org_place_name' => $this->org_place_name,
            'district_id' => $this->district_id,
            'unit' => $this->unit,
            'daily_rate' => $this->daily_rate,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Tarif uang harian kecamatan berhasil diperbarui.');

        return redirect()->route('district-perdiem-rates.index');
    }

    public function render()
    {
        $districts = District::orderBy('name')->get();

        return view('livewire.district-perdiem-rates.edit', [
            'districts' => $districts,
        ]);
    }
}
