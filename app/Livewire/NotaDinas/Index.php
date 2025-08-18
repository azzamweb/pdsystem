<?php

namespace App\Livewire\NotaDinas;

use App\Models\NotaDinas;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Nota Dinas'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function delete($id)
    {
        $nd = NotaDinas::findOrFail($id);
        $nd->delete();
        session()->flash('message', 'Nota Dinas berhasil dihapus.');
    }

    public function render()
    {
        $notaDinas = NotaDinas::with(['requestingUnit', 'destinationCity', 'participants.user'])
            ->when($this->search, function($q) {
                $q->where('doc_no', 'like', '%'.$this->search.'%')
                  ->orWhereHas('requestingUnit', function($q2) { $q2->where('name', 'like', '%'.$this->search.'%'); })
                  ->orWhereHas('destinationCity', function($q2) { $q2->where('name', 'like', '%'.$this->search.'%'); })
                  ->orWhere('status', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('doc_no')
            ->orderByDesc('nd_date')
            ->paginate(10);
        return view('livewire.nota-dinas.index', [
            'notaDinas' => $notaDinas
        ]);
    }
}
