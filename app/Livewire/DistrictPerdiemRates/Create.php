<?php

namespace App\Livewire\DistrictPerdiemRates;

use App\Models\DistrictPerdiemRate;
use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Create extends Component
{
    #[Rule('required|string|max:255')]
    public $org_place_name = '';

    #[Rule('required|exists:districts,id')]
    public $district_id = '';

    #[Rule('required|string|max:10')]
    public $unit = 'OH';

    #[Rule('required|numeric|min:0')]
    public $daily_rate = '';

    public $is_active = true;

    public function save()
    {
        $this->validate();

        // Check for duplicate
        $existing = DistrictPerdiemRate::where('org_place_name', $this->org_place_name)
            ->where('district_id', $this->district_id)
            ->first();

        if ($existing) {
            $this->addError('org_place_name', 'Tarif untuk kedudukan dan kecamatan ini sudah ada.');
            return;
        }

        DistrictPerdiemRate::create([
            'org_place_name' => $this->org_place_name,
            'district_id' => $this->district_id,
            'unit' => $this->unit,
            'daily_rate' => $this->daily_rate,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Tarif uang harian kecamatan berhasil ditambahkan.');

        return redirect()->route('district-perdiem-rates.index');
    }

    public function render()
    {
        $districts = District::orderBy('name')->get();

        return view('livewire.district-perdiem-rates.create', [
            'districts' => $districts,
        ]);
    }
}
