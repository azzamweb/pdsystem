<?php

namespace App\Livewire\RepresentationRates;

use App\Models\RepresentationRate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        $representationRate = RepresentationRate::findOrFail($id);
        $representationRate->delete();
        session()->flash('message', 'Tarif representasi berhasil dihapus');
    }

    public function render()
    {
        $representationRates = RepresentationRate::query()
            ->with('travelGrade')
            ->when($this->search, function ($query) {
                $query->whereHas('travelGrade', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.representation-rates.index', [
            'representationRates' => $representationRates
        ]);
    }
}
