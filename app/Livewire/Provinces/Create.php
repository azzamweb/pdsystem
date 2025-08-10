<?php

namespace App\Livewire\Provinces;

use App\Models\Province;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Create extends Component
{
    public $kemendagri_code = '';
    public $name = '';

    protected $rules = [
        'kemendagri_code' => 'required|string|max:10|unique:provinces,kemendagri_code',
        'name' => 'required|string|max:100',
    ];

    protected $messages = [
        'kemendagri_code.required' => 'Kode Kemendagri wajib diisi',
        'kemendagri_code.unique' => 'Kode Kemendagri sudah ada',
        'name.required' => 'Nama provinsi wajib diisi',
    ];

    public function save()
    {
        $this->validate();

        Province::create([
            'kemendagri_code' => $this->kemendagri_code,
            'name' => $this->name,
        ]);

        session()->flash('message', 'Provinsi berhasil ditambahkan');
        return redirect()->route('provinces.index');
    }

    public function render()
    {
        return view('livewire.provinces.create');
    }
}
