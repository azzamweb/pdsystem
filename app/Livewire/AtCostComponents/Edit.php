<?php

namespace App\Livewire\AtCostComponents;

use App\Models\AtCostComponent;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public AtCostComponent $component;

    #[Rule('required|string|max:50')]
    public $code = '';

    #[Rule('required|string|max:255')]
    public $name = '';

    public function mount(AtCostComponent $component)
    {
        $this->component = $component;
        $this->code = $this->component->code;
        $this->name = $this->component->name;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|string|max:50|unique:atcost_components,code,' . $this->component->id,
            'name' => 'required|string|max:255',
        ]);

        $this->component->update([
            'code' => strtoupper($this->code),
            'name' => $this->name,
        ]);

        session()->flash('message', 'Komponen at-cost berhasil diperbarui');
        return $this->redirect(route('at-cost-components.index'));
    }

    public function render()
    {
        return view('livewire.at-cost-components.edit');
    }
}
