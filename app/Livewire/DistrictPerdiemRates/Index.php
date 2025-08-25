<?php

namespace App\Livewire\DistrictPerdiemRates;

use App\Models\DistrictPerdiemRate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $orgPlaceFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'orgPlaceFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOrgPlaceFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DistrictPerdiemRate::with(['district'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('org_place_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('district', function ($districtQuery) {
                          $districtQuery->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->orgPlaceFilter, function ($query) {
                $query->where('org_place_name', $this->orgPlaceFilter);
            })
            ->orderBy('org_place_name')
            ->orderBy('daily_rate', 'desc');

        $districtPerdiemRates = $query->paginate(10);

        $orgPlaces = DistrictPerdiemRate::distinct()
            ->pluck('org_place_name')
            ->sort()
            ->values();

        return view('livewire.district-perdiem-rates.index', [
            'districtPerdiemRates' => $districtPerdiemRates,
            'orgPlaces' => $orgPlaces,
        ]);
    }
}
