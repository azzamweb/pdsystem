<?php

namespace App\Livewire\NotaDinas;

use App\Models\NotaDinas;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public NotaDinas $notaDinas;

    public function render()
    {
        $this->notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser']);
        return view('livewire.nota-dinas.show', [
            'notaDinas' => $this->notaDinas
        ]);
    }
}
