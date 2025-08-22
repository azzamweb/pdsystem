<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\NotaDinas;
use App\Models\Spt;
use App\Models\Sppd;


class MainPage extends Component
{
    public $selectedNotaDinasId = null;
    public $selectedSptId = null;
    public $selectedSppdId = null;
    
    public $selectedNotaDinas = null;
    public $selectedSpt = null;
    public $selectedSppd = null;

    // Computed properties for child components
    public function getCurrentNotaDinas()
    {
        if ($this->selectedNotaDinasId) {
            return NotaDinas::with(['spt.sppds'])->find($this->selectedNotaDinasId);
        }
        return null;
    }

    public function getCurrentSpt()
    {
        if ($this->selectedSptId) {
            return Spt::with(['sppds'])->find($this->selectedSptId);
        }
        return null;
    }

    #[On('ndSelected')]
    public function handleNotaDinasSelected($notaDinasId)
    {
        $this->selectedNotaDinasId = $notaDinasId;
        $this->selectedSptId = null;
        $this->selectedSppdId = null;
        
        // Dispatch specific events to child components
        $this->dispatch('loadSpts', $notaDinasId);
        $this->dispatch('clearSppds');
    }

    #[On('sptSelected')]
    public function handleSptSelected($sptId)
    {
        $this->selectedSptId = $sptId;
        $this->selectedSppdId = null;
        
        // Dispatch specific event to SppdTable
        $this->dispatch('loadSppds', $sptId);
    }

    #[On('sppdSelected')]
    public function handleSppdSelected($sppdId)
    {
        $this->selectedSppdId = $sppdId;
    }

    #[On('refreshAll')]
    public function refreshData()
    {
        // Refresh selected data
        if ($this->selectedNotaDinasId) {
            $this->selectedNotaDinas = NotaDinas::with(['spt.sppds'])->find($this->selectedNotaDinasId);
        }
        if ($this->selectedSptId) {
            $this->selectedSpt = Spt::with(['sppds'])->find($this->selectedSptId);
        }
        if ($this->selectedSppdId) {
            $this->selectedSppd = Sppd::find($this->selectedSppdId);
        }
    }

    public function mount()
    {
        // Initialize with latest Nota Dinas if available
        $latestNd = NotaDinas::with(['spt.sppds'])->latest('created_at')->first();
        if ($latestNd) {
            $this->selectedNotaDinasId = $latestNd->id;
            // Dispatch events to load child components
            $this->dispatch('loadSpts', $latestNd->id);
            if ($latestNd->spt) {
                $this->selectedSptId = $latestNd->spt->id;
                $this->dispatch('loadSppds', $latestNd->spt->id);
            }
        }
    }



    public function createKwitansi()
    {
        if (!$this->selectedSppd) {
            session()->flash('error', 'Pilih SPPD terlebih dahulu');
            return;
        }
        
        // TODO: Implement kwitansi creation
        session()->flash('message', 'Fitur Buat Kwitansi akan segera tersedia');
    }

    public function createDaftarRiil()
    {
        if (!$this->selectedSppd) {
            session()->flash('error', 'Pilih SPPD terlebih dahulu');
            return;
        }
        
        // TODO: Implement daftar riil creation
        session()->flash('message', 'Fitur Isi Daftar Riil akan segera tersedia');
    }

    public function createLaporanPd()
    {
        if (!$this->selectedSpt) {
            session()->flash('error', 'Pilih SPT terlebih dahulu');
            return;
        }
        
        // TODO: Implement laporan PD creation
        session()->flash('message', 'Fitur Buat Laporan PD akan segera tersedia');
    }

    public function render()
    {
        return view('livewire.documents.main-page');
    }
}
