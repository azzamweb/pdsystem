<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Sppd;
use App\Models\Spt;


class SppdTable extends Component
{
    public $sptId = null;
    public $selectedSppdId = null;
    public $sppds = [];

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
        
        if ($sptId) {
            $spt = Spt::with(['sppds.user', 'sppds.originPlace', 'sppds.destinationCity'])->find($sptId);
            if ($spt) {
                $this->sppds = $spt->sppds->sortByDesc('created_at')->values();
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

    public function render()
    {
        return view('livewire.documents.sppd-table');
    }
}
