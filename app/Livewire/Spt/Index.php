<?php

namespace App\Livewire\Spt;

use App\Models\Spt;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    // Status dihapus dari SPT; filter status tidak digunakan lagi
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        // 'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $spts = Spt::with(['notaDinas', 'signedByUser'])
            ->when($this->search, function($q) {
                $q->where('doc_no', 'like', '%'.$this->search.'%')
                  ->orWhereHas('notaDinas', function($q2) { 
                      $q2->where('hal', 'like', '%'.$this->search.'%'); 
                  })
                  ->orWhere('spt_date', 'like', '%'.$this->search.'%')
                  ->orWhere('status', 'like', '%'.$this->search.'%');
            })
            // status filter dihapus
            ->orderByDesc('spt_date')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.spt.index', [
            'spts' => $spts
        ]);
    }
}
