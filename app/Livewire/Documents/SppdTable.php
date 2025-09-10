<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Sppd;
use App\Models\Spt;
use App\Models\NotaDinas;


class SppdTable extends Component
{
    public $sptId = null;
    public $selectedSppdId = null;
    public $sppds = [];
    public $spt = null; // Store SPT data

    protected $listeners = [];

    public function mount($sptId = null)
    {
        $this->sptId = $sptId;
        if ($sptId) {
            $this->loadSppds($sptId);
        }
    }

    #[On('loadSppds')]
    public function handleLoadSppds($sptId)
    {
        $this->loadSppds($sptId);
    }

    #[On('clearSppds')]
    public function handleClearSppds()
    {
        $this->sptId = null;
        $this->selectedSppdId = null;
        $this->sppds = [];
    }

    public function loadSppds($sptId)
    {
        // Always reset state
        $this->sptId = $sptId;
        $this->selectedSppdId = null;
        $this->sppds = [];
        $this->spt = null;
        
        if ($sptId) {
            $this->spt = Spt::with(['sppds.transportModes', 'sppds.receipts', 'sppds.itineraries', 'sppds.subKeg.pptkUser', 'tripReport', 'notaDinas.originPlace', 'notaDinas.destinationCity.province', 'notaDinas.participants.user'])->find($sptId);
            if ($this->spt) {
                $this->sppds = $this->spt->sppds->sortByDesc('created_at')->values();
                // Don't auto-select SPPD
            }
        }
    }

    public function selectSppd($sppdId)
    {
        $this->selectedSppdId = $sppdId;
        $this->dispatch('sppdSelected', $sppdId);
    }



    public function createSppd($sptId)
    {
        return redirect()->route('sppd.create', ['spt_id' => $sptId]);
    }





    public function confirmDelete($sppdId)
    {
        $sppd = Sppd::find($sppdId);
        if ($sppd) {
            // Cek apakah SPPD sudah memiliki data terkait (itinerary, kwitansi, dll)
            if ($sppd->itineraries && $sppd->itineraries->count() > 0) {
                session()->flash('error', 'SPPD tidak dapat dihapus karena masih memiliki data rute perjalanan. Hapus data rute perjalanan terlebih dahulu.');
                return;
            }
            
            // Cek apakah SPPD sudah memiliki kwitansi atau laporan
            if ($sppd->receipts && $sppd->receipts->count() > 0) {
                session()->flash('error', 'SPPD tidak dapat dihapus karena sudah memiliki data kwitansi. Hapus data kwitansi terlebih dahulu.');
                return;
            }
            
            // Cek apakah SPPD sudah memiliki laporan perjalanan dinas (melalui SPT)
            if ($sppd->spt && $sppd->spt->tripReport) {
                session()->flash('error', 'SPPD tidak dapat dihapus karena SPT sudah memiliki laporan perjalanan dinas. Hapus laporan terlebih dahulu.');
                return;
            }
            
            try {
                $sppd->delete();
                session()->flash('message', 'SPPD berhasil dihapus');
                $this->dispatch('refreshAll');
                // Reload SPPDs after deletion
                $this->loadSppds($this->sptId);
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus SPPD. Pastikan tidak ada data terkait.');
            }
        } else {
            session()->flash('error', 'SPPD tidak ditemukan');
        }
    }



    public function render()
    {
        return view('livewire.documents.sppd-table');
    }
}
