<?php

namespace App\Livewire\Provinces;

use App\Models\Province;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public Province $province;
    public $kemendagri_code = '';
    public $name = '';

    protected $rules = [
        'kemendagri_code' => 'required|string|max:10',
        'name' => 'required|string|max:100',
    ];

    protected $messages = [
        'kemendagri_code.required' => 'Kode Kemendagri wajib diisi',
        'name.required' => 'Nama provinsi wajib diisi',
    ];

    public function mount(Province $province)
    {
        $this->province = $province;
        $this->kemendagri_code = $province->kemendagri_code;
        $this->name = $province->name;
    }

    public function update()
    {
        $this->validate([
            'kemendagri_code' => 'required|string|max:10|unique:provinces,kemendagri_code,' . $this->province->id,
            'name' => 'required|string|max:100',
        ]);

        $this->province->update([
            'kemendagri_code' => $this->kemendagri_code,
            'name' => $this->name,
        ]);

        session()->flash('message', 'Provinsi berhasil diperbarui');
        return redirect()->route('provinces.index');
    }

    public function render()
    {
        return view('livewire.provinces.edit');
    }
}
