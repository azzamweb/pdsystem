<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Spt;
use App\Models\NotaDinas;

class SptTable extends Component
{
    public $notaDinasId = null;
    public $selectedSptId = null;
    public $spts = [];
    public $notaDinas = null;

    protected $listeners = [];

    public function mount($notaDinasId = null)
    {
        $this->notaDinasId = $notaDinasId;
        if ($notaDinasId) {
            $this->loadSpts($notaDinasId);
        }
    }

    #[On('loadSpts')]
    public function handleLoadSpts($notaDinasId)
    {
        $this->loadSpts($notaDinasId);
    }

    public function loadSpts($notaDinasId)
    {
        // Always reset state
        $this->notaDinasId = $notaDinasId;
        $this->selectedSptId = null;
        $this->spts = [];
        $this->notaDinas = null;
        
        if ($notaDinasId) {
            $this->notaDinas = NotaDinas::with(['spt.sppds.user', 'spt.signedByUser'])->find($notaDinasId);
            if ($this->notaDinas && $this->notaDinas->spt) {
                $this->spts = [$this->notaDinas->spt];
                // Don't auto-select SPT
            }
        }
    }

    public function selectSpt($sptId)
    {
        $this->selectedSptId = $sptId;
        $this->dispatch('sptSelected', $sptId);
    }

    public function createSppd($sptId)
    {
        $spt = Spt::find($sptId);
        if ($spt) {
            return redirect()->route('sppd.create', ['spt_id' => $sptId]);
        }
        
        session()->flash('error', 'SPT tidak ditemukan');
    }

    public function createSpt($notaDinasId)
    {
        return redirect()->route('spt.create', ['nota_dinas_id' => $notaDinasId]);
    }

    public function render()
    {
        return view('livewire.documents.spt-table');
    }
}
