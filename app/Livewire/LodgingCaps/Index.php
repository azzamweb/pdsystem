<?php

namespace App\Livewire\LodgingCaps;

use App\Models\LodgingCap;
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
        $lodgingCap = LodgingCap::findOrFail($id);
        $lodgingCap->delete();
        session()->flash('message', 'Batas tarif penginapan berhasil dihapus');
    }

    public function render()
    {
        $lodgingCaps = LodgingCap::query()
            ->with(['province', 'travelGrade'])
            ->when($this->search, function ($query) {
                $query->whereHas('province', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('kemendagri_code', 'like', '%' . $this->search . '%');
                })->orWhereHas('travelGrade', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.lodging-caps.index', [
            'lodgingCaps' => $lodgingCaps
        ]);
    }
}
