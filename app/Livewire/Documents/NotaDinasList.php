<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\NotaDinas;
use App\Models\Unit;
use Carbon\Carbon;

class NotaDinasList extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $unitFilter = '';
    public $statusFilter = '';
    
    public $selectedNotaDinasId = null;

    public function mount($selectedNotaDinasId = null)
    {
        $this->selectedNotaDinasId = $selectedNotaDinasId;
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'unitFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    protected $listeners = [
        'refreshList' => '$refresh'
    ];

    public function updatedSearch()
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

    public function updatedUnitFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->unitFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function selectNotaDinas($notaDinasId)
    {
        $this->selectedNotaDinasId = $notaDinasId;
        $this->dispatch('ndSelected', $notaDinasId);
    }



    public function updateStatus($notaDinasId, $newStatus)
    {
        $notaDinas = NotaDinas::find($notaDinasId);
        if ($notaDinas && in_array($newStatus, ['DRAFT', 'APPROVED'])) {
            $notaDinas->update(['status' => $newStatus]);
            $this->dispatch('refreshAll');
            session()->flash('message', 'Status berhasil diperbarui');
        }
    }

    public function createSpt($notaDinasId)
    {
        $notaDinas = NotaDinas::find($notaDinasId);
        if ($notaDinas && $notaDinas->status === 'APPROVED' && !$notaDinas->spt) {
            return redirect()->route('spt.create', ['nota_dinas_id' => $notaDinasId]);
        }
        
        session()->flash('error', 'Tidak dapat membuat SPT. Pastikan Nota Dinas sudah APPROVED dan belum memiliki SPT.');
    }

    public function confirmDelete($notaDinasId)
    {
        $notaDinas = NotaDinas::with(['spt', 'supportingDocuments'])->find($notaDinasId);
        if ($notaDinas) {
            // Cek apakah Nota Dinas sudah memiliki SPT yang aktif
            if ($notaDinas->spt && $notaDinas->spt->exists) {
                session()->flash('error', 'Nota Dinas tidak dapat dihapus karena sudah memiliki Surat Perintah Tugas (SPT). Hapus SPT terlebih dahulu.');
                return;
            }
            
            // Cek apakah ada dokumen pendukung yang aktif
            if ($notaDinas->supportingDocuments && $notaDinas->supportingDocuments->where('is_active', true)->count() > 0) {
                session()->flash('error', 'Nota Dinas tidak dapat dihapus karena masih memiliki dokumen pendukung. Hapus dokumen pendukung terlebih dahulu.');
                return;
            }
            
            try {
                $notaDinas->delete();
                session()->flash('message', 'Nota Dinas berhasil dihapus');
                $this->dispatch('refreshAll');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus Nota Dinas. Pastikan tidak ada data terkait.');
            }
        } else {
            session()->flash('error', 'Nota Dinas tidak ditemukan');
        }
    }

    public function deleteNotaDinas($notaDinasId)
    {
        $this->confirmDelete($notaDinasId);
    }

    public function render()
    {
        $query = NotaDinas::with(['spt', 'requestingUnit', 'toUser', 'fromUser', 'participants.user.position.echelon', 'participants.user.rank', 'destinationCity', 'supportingDocuments' => function($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('created_at', 'desc');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('doc_no', 'like', '%' . $this->search . '%')
                  ->orWhere('hal', 'like', '%' . $this->search . '%')
                  ->orWhere('dasar', 'like', '%' . $this->search . '%')
                  ->orWhere('maksud', 'like', '%' . $this->search . '%')
                  ->orWhereHas('requestingUnit', function($unitQuery) {
                      $unitQuery->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('destinationCity', function($cityQuery) {
                      $cityQuery->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('participants.user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('nip', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('nd_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('nd_date', '<=', $this->dateTo);
        }

        // Unit filter
        if ($this->unitFilter) {
            $query->where('requesting_unit_id', $this->unitFilter);
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $notaDinasList = $query->paginate(10);
        $units = Unit::orderBy('name')->get();

        return view('livewire.documents.nota-dinas-list', [
            'notaDinasList' => $notaDinasList,
            'units' => $units,
        ]);
    }
}
