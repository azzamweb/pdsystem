<?php

namespace App\Livewire\PerdiemRates;

use App\Models\PerdiemRate;
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
        $perdiemRate = PerdiemRate::findOrFail($id);
        $perdiemRate->delete();
        session()->flash('message', 'Tarif uang harian berhasil dihapus');
    }

    public function render()
    {
        $perdiemRates = PerdiemRate::query()
            ->with(['province'])
            ->when($this->search, function ($query) {
                $query->whereHas('province', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('kemendagri_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.perdiem-rates.index', [
            'perdiemRates' => $perdiemRates
        ]);
    }
}
