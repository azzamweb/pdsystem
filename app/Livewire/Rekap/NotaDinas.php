<?php

namespace App\Livewire\Rekap;

use App\Models\NotaDinas as NotaDinasModel;
use App\Models\Unit;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class NotaDinas extends Component
{
    use WithPagination;

    public $search = '';
    public $unit_filter = '';
    public $status_filter = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 10;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedUnitFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = NotaDinasModel::with([
            'requestingUnit',
            'toUser',
            'fromUser',
            'destinationCity',
            'participants.user',
            'createdBy'
        ]);

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('doc_no', 'like', '%' . $this->search . '%')
                  ->orWhere('hal', 'like', '%' . $this->search . '%')
                  ->orWhere('dasar', 'like', '%' . $this->search . '%')
                  ->orWhere('maksud', 'like', '%' . $this->search . '%')
                  ->orWhereHas('toUser', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('fromUser', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->unit_filter) {
            $query->where('requesting_unit_id', $this->unit_filter);
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        if ($this->date_from) {
            $query->where('nd_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->where('nd_date', '<=', $this->date_to);
        }

        $notaDinas = $query->orderBy('nd_date', 'desc')->paginate($this->perPage);

        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Statistics
        $totalNotaDinas = NotaDinasModel::count();
        $draftCount = NotaDinasModel::where('status', 'DRAFT')->count();
        $approvedCount = NotaDinasModel::where('status', 'APPROVED')->count();
        $rejectedCount = NotaDinasModel::where('status', 'REJECTED')->count();

        return view('livewire.rekap.nota-dinas', [
            'notaDinas' => $notaDinas,
            'units' => $units,
            'users' => $users,
            'totalNotaDinas' => $totalNotaDinas,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }
}
