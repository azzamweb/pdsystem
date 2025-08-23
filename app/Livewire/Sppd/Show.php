<?php

namespace App\Livewire\Sppd;

use App\Models\Sppd;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public $sppd_id = null;
    public $sppd = null;

    public function mount($sppd_id = null): void
    {
        $this->sppd_id = $sppd_id ?? request()->route('sppd');
        if (!$this->sppd_id) {
            session()->flash('error', 'SPPD tidak ditemukan.');
            $this->redirect(route('sppd.index'));
            return;
        }

        $this->sppd = Sppd::with([
            'user', 
            'spt.notaDinas', 
            'originPlace', 
            'destinationCity', 
            'transportMode',
            'itineraries'
        ])->findOrFail($this->sppd_id);
    }

    public function render()
    {
        return view('livewire.sppd.show');
    }
}
