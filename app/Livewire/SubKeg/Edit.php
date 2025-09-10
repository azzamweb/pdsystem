<?php

namespace App\Livewire\SubKeg;

use Livewire\Component;
use App\Models\SubKeg;
use App\Models\Unit;
use App\Models\User;

class Edit extends Component
{
    public SubKeg $subKeg;
    public $kode_subkeg = '';
    public $nama_subkeg = '';
    public $pagu = '';
    public $id_unit = '';
    public $pptk_user_id = '';

    protected $rules = [
        'kode_subkeg' => 'required|string|max:255',
        'nama_subkeg' => 'required|string|max:255',
        'pagu' => 'nullable|numeric|min:0',
        'id_unit' => 'required|exists:units,id',
        'pptk_user_id' => 'nullable|exists:users,id',
    ];

    protected $messages = [
        'kode_subkeg.required' => 'Kode Sub Kegiatan harus diisi.',
        'nama_subkeg.required' => 'Nama Sub Kegiatan harus diisi.',
        'id_unit.required' => 'Unit harus dipilih.',
        'id_unit.exists' => 'Unit yang dipilih tidak valid.',
        'pagu.numeric' => 'Pagu harus berupa angka.',
        'pagu.min' => 'Pagu tidak boleh negatif.',
        'pptk_user_id.exists' => 'PPTK yang dipilih tidak valid.',
    ];

    public function mount(SubKeg $subKeg)
    {
        $this->subKeg = $subKeg;
        $this->kode_subkeg = $subKeg->kode_subkeg;
        $this->nama_subkeg = $subKeg->nama_subkeg;
        $this->pagu = $subKeg->pagu;
        $this->id_unit = $subKeg->id_unit;
        $this->pptk_user_id = $subKeg->pptk_user_id;
    }

    public function update()
    {
        $this->rules['kode_subkeg'] = 'required|string|max:255|unique:sub_keg,kode_subkeg,' . $this->subKeg->id;
        
        $this->validate();

        $data = [
            'kode_subkeg' => $this->kode_subkeg,
            'nama_subkeg' => $this->nama_subkeg,
            'pagu' => $this->pagu ?: null,
            'id_unit' => $this->id_unit,
            'pptk_user_id' => $this->pptk_user_id ?: null,
        ];

        $this->subKeg->update($data);

        session()->flash('success', 'Sub Kegiatan berhasil diperbarui.');
        
        return redirect()->route('sub-keg.index');
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        return view('livewire.sub-keg.edit', [
            'units' => $units,
            'users' => $users,
        ]);
    }
}
