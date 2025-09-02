<?php

namespace App\Livewire\DistrictPerdiemRates;

use App\Models\DistrictPerdiemRate;
use App\Models\District;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Create extends Component
{
    #[Rule('required|exists:districts,id')]
    public $district_id = '';

    #[Rule('required|exists:travel_grades,id')]
    public $travel_grade_id = '';

    #[Rule('required|numeric|min:0')]
    public $perdiem_rate = '';

    public function save()
    {
        $this->validate();

        // Check for duplicate
        $existing = DistrictPerdiemRate::where('district_id', $this->district_id)
            ->where('travel_grade_id', $this->travel_grade_id)
            ->first();

        if ($existing) {
            $this->addError('district_id', 'Tarif untuk kecamatan dan tingkatan ini sudah ada.');
            return;
        }

        DistrictPerdiemRate::create([
            'district_id' => $this->district_id,
            'travel_grade_id' => $this->travel_grade_id,
            'perdiem_rate' => $this->perdiem_rate,
        ]);

        session()->flash('message', 'Tarif uang harian kecamatan berhasil ditambahkan.');

        return redirect()->route('district-perdiem-rates.index');
    }

    public function render()
    {
        $districts = District::orderBy('name')->get();
        $travelGrades = \App\Models\TravelGrade::orderBy('name')->get();

        return view('livewire.district-perdiem-rates.create', [
            'districts' => $districts,
            'travelGrades' => $travelGrades,
        ]);
    }
}
