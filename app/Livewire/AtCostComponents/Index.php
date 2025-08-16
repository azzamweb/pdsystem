<?php

namespace App\Livewire\AtCostComponents;

use App\Models\AtCostComponent;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Data Komponen At-Cost'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(AtCostComponent $component)
    {
        try {
            $component->delete();
            session()->flash('message', 'Komponen at-cost berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus komponen at-cost. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $components = AtCostComponent::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('code')
            ->paginate(10);

        return view('livewire.at-cost-components.index', [
            'components' => $components
        ]);
    }
}
