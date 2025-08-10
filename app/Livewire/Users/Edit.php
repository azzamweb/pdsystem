<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Unit;
use App\Models\Position;
use App\Models\Rank;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Edit Pegawai'])]

class Edit extends Component
{
    public User $user;

    // Form properties
    public $name = '';
    public $email = '';
    public $nip = '';
    public $nik = '';
    public $gelar_depan = '';
    public $gelar_belakang = '';
    public $phone = '';
    public $whatsapp = '';
    public $address = '';
    public $unit_id = '';
    public $position_id = '';
    public $rank_id = '';
    public $npwp = '';
    public $bank_name = '';
    public $bank_account_no = '';
    public $bank_account_name = '';
    public $birth_date = '';
    public $gender = '';
    public $is_signer = false;

    // Mutators to handle empty strings for foreign key fields
    public function setUnitIdProperty($value)
    {
        $this->unit_id = $value === '' ? null : $value;
    }

    public function setPositionIdProperty($value)
    {
        $this->position_id = $value === '' ? null : $value;
    }

    public function setRankIdProperty($value)
    {
        $this->rank_id = $value === '' ? null : $value;
    }



    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->nip = $user->nip;
        $this->nik = $user->nik;
        $this->gelar_depan = $user->gelar_depan;
        $this->gelar_belakang = $user->gelar_belakang;
        $this->phone = $user->phone;
        $this->whatsapp = $user->whatsapp;
        $this->address = $user->address;
        $this->unit_id = $user->unit_id;
        $this->position_id = $user->position_id;
        $this->rank_id = $user->rank_id;

        $this->npwp = $user->npwp;
        $this->bank_name = $user->bank_name;
        $this->bank_account_no = $user->bank_account_no;
        $this->bank_account_name = $user->bank_account_name;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->gender = $user->gender;
        $this->is_signer = $user->is_signer;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'nip' => 'nullable|string|max:20|unique:users,nip,' . $this->user->id,
            'nik' => 'nullable|string|max:20|unique:users,nik,' . $this->user->id,
            'gelar_depan' => 'nullable|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'position_id' => 'nullable|exists:positions,id',
            'rank_id' => 'nullable|exists:ranks,id',

            'npwp' => 'nullable|string|max:25',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'is_signer' => 'boolean',
        ];
    }

    public function update()
    {
        $validated = $this->validate();
        
        // Convert empty strings to null for foreign key fields
        $foreignKeys = ['unit_id', 'position_id', 'rank_id'];
        foreach ($foreignKeys as $key) {
            if (isset($validated[$key]) && $validated[$key] === '') {
                $validated[$key] = null;
            }
        }
        
        $this->user->update($validated);
        
        session()->flash('message', 'Data pegawai berhasil diperbarui.');
        
        return redirect()->route('users.index');
    }

    public function render()
    {
        $units = Unit::orderBy('name')->get();
        $positions = Position::with('echelon')
            ->leftJoin('echelons', 'positions.echelon_id', '=', 'echelons.id')
            ->orderByRaw('CASE WHEN echelons.code IS NULL THEN 2 ELSE 0 END') // Non eselon positions last
            ->orderBy('echelons.code', 'asc') // Eselon tertinggi (I.a) first
            ->orderBy('positions.name')
            ->select('positions.*')
            ->get();
        $ranks = Rank::orderBy('code', 'desc')->get(); // Pangkat tertinggi (IV/e) first

        return view('livewire.users.edit', compact('units', 'positions', 'ranks'));
    }
}
