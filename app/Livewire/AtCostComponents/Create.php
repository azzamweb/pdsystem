<?php

namespace App\Livewire\AtCostComponents;

use App\Models\AtCostComponent;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Create extends Component
{
    #[Rule('required|string|max:50|unique:atcost_components,code')]
    public $code = '';

    #[Rule('required|string|max:255')]
    public $name = '';

    public function save()
    {
        $this->validate();

        AtCostComponent::create([
            'code' => strtoupper($this->code),
            'name' => $this->name,
        ]);

        session()->flash('message', 'Komponen at-cost berhasil ditambahkan');
        return $this->redirect(route('at-cost-components.index'));
    }

    public function render()
    {
        return view('livewire.at-cost-components.create');
    }
}
