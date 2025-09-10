<?php

namespace App\Livewire\SubKeg;

use Livewire\Component;
use App\Models\SubKeg;
use App\Models\Unit;
use App\Models\User;

class Create extends Component
{
    public $kode_subkeg = '';
    public $nama_subkeg = '';
    public $pagu = '';
    public $id_unit = '';
    public $pptk_user_id = '';

    protected $rules = [
        'kode_subkeg' => 'required|string|max:255|unique:sub_keg,kode_subkeg',
        'nama_subkeg' => 'required|string|max:255',
        'pagu' => 'nullable|numeric|min:0',
        'id_unit' => 'required|exists:units,id',
        'pptk_user_id' => 'nullable|exists:users,id',
    ];

    protected $messages = [
        'kode_subkeg.required' => 'Kode Sub Kegiatan harus diisi.',
        'kode_subkeg.unique' => 'Kode Sub Kegiatan sudah digunakan.',
        'nama_subkeg.required' => 'Nama Sub Kegiatan harus diisi.',
        'id_unit.required' => 'Unit harus dipilih.',
        'id_unit.exists' => 'Unit yang dipilih tidak valid.',
        'pagu.numeric' => 'Pagu harus berupa angka.',
        'pagu.min' => 'Pagu tidak boleh negatif.',
        'pptk_user_id.exists' => 'PPTK yang dipilih tidak valid.',
    ];

    public function store()
    {
        $this->validate();

        $data = [
            'kode_subkeg' => $this->kode_subkeg,
            'nama_subkeg' => $this->nama_subkeg,
            'pagu' => $this->pagu ?: null,
            'id_unit' => $this->id_unit,
            'pptk_user_id' => $this->pptk_user_id ?: null,
        ];

        SubKeg::create($data);

        session()->flash('success', 'Sub Kegiatan berhasil ditambahkan.');
        
        return redirect()->route('sub-keg.index');
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        return view('livewire.sub-keg.create', [
            'units' => $units,
            'users' => $users,
        ]);
    }
}
