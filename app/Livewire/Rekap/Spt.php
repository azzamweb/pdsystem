<?php

namespace App\Livewire\Rekap;

use App\Models\Spt as SptModel;
use App\Models\Unit;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Spt extends Component
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
        $query = SptModel::with([
            'notaDinas.requestingUnit',
            'notaDinas.toUser',
            'notaDinas.fromUser',
            'notaDinas.destinationCity',
            'notaDinas.participants.user',
            'signer',
            'createdBy'
        ]);

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('doc_no', 'like', '%' . $this->search . '%')
                  ->orWhere('assignment_title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('notaDinas', function($q2) {
                      $q2->where('hal', 'like', '%' . $this->search . '%')
                         ->orWhere('dasar', 'like', '%' . $this->search . '%')
                         ->orWhere('maksud', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('signer', function($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->unit_filter) {
            $query->whereHas('notaDinas', function($q) {
                $q->where('requesting_unit_id', $this->unit_filter);
            });
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        if ($this->date_from) {
            $query->where('spt_date', '>=', $this->date_from);
        }

        if ($this->date_to) {
            $query->where('spt_date', '<=', $this->date_to);
        }

        $spts = $query->orderBy('spt_date', 'desc')->paginate($this->perPage);

        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Statistics
        $totalSpt = SptModel::count();
        $draftCount = SptModel::where('status', 'DRAFT')->count();
        $approvedCount = SptModel::where('status', 'APPROVED')->count();
        $rejectedCount = SptModel::where('status', 'REJECTED')->count();

        return view('livewire.rekap.spt', [
            'spts' => $spts,
            'units' => $units,
            'users' => $users,
            'totalSpt' => $totalSpt,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }
}
