<?php

namespace App\Livewire\SubKeg;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubKeg;
use App\Models\Unit;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $unitFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'unitFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $subKeg = SubKeg::findOrFail($id);
        $subKeg->delete();
        
        session()->flash('success', 'Sub Kegiatan berhasil dihapus.');
    }

    public function render()
    {
        $query = SubKeg::with(['unit', 'pptkUser']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('kode_subkeg', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_subkeg', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->unitFilter) {
            $query->where('id_unit', $this->unitFilter);
        }

        $subKegiatan = $query->orderBy('kode_subkeg')->paginate($this->perPage);
        $units = Unit::orderBy('name')->get();

        return view('livewire.sub-keg.index', [
            'subKegiatan' => $subKegiatan,
            'units' => $units,
        ]);
    }
}
