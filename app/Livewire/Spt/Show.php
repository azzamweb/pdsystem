<?php

namespace App\Livewire\Spt;

use App\Models\Spt;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Spt $spt;

    public function mount(Spt $spt)
    {
        $this->spt = $spt;
    }

    public function render()
    {
        $this->spt->load([
            'notaDinas.requestingUnit',
            'notaDinas.toUser.rank',
            'notaDinas.toUser.position',
            'notaDinas.fromUser',
            'signedByUser.rank',
            'signedByUser.position',
            // Peserta akan diambil dari nota dinas
            'notaDinas.participants.user.rank',
            'notaDinas.participants.user.position',
        ]);
        return view('livewire.spt.show', [
            'spt' => $this->spt,
        ]);
    }
}
