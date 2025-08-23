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
            $this->spt = Spt::with(['sppds.user', 'sppds.originPlace', 'sppds.destinationCity', 'notaDinas.participants.user'])->find($sptId);
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

    public function getParticipantsWithoutSppd()
    {
        if (!$this->spt || !$this->spt->notaDinas) {
            return collect();
        }

        // Get all participants from Nota Dinas
        $allParticipants = $this->spt->notaDinas->participants;
        
        // Get user IDs that already have SPPD
        $existingSppdUserIds = $this->spt->sppds->pluck('user_id')->toArray();
        
        // Filter participants who don't have SPPD yet
        $participantsWithoutSppd = $allParticipants->whereNotIn('user_id', $existingSppdUserIds);
        

        
        return $participantsWithoutSppd;
    }

    public function render()
    {
        return view('livewire.documents.sppd-table');
    }
}
