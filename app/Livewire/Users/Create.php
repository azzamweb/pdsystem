<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Unit;
use App\Models\Position;
use App\Models\Rank;
use App\Models\TravelGrade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Tambah Pegawai'])]

class Create extends Component
{
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
    public $position_desc = '';
    public $rank_id = '';
    public $npwp = '';
    public $bank_name = '';
    public $bank_account_no = '';
    public $bank_account_name = '';
    public $birth_date = '';
    public $gender = '';
    public $is_signer = false;
    public $is_non_staff = false;
    public $travel_grade_id = '';

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

    public function setTravelGradeIdProperty($value)
    {
        $this->travel_grade_id = $value === '' ? null : $value;
    }

    public function setBirthDateProperty($value)
    {
        $this->birth_date = ($value === '' || $value === null) ? null : $value;
    }



    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nip' => 'nullable|string|max:20|unique:users,nip',
            'nik' => 'nullable|string|max:20|unique:users,nik',
            'gelar_depan' => 'nullable|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'unit_id' => 'nullable|exists:units,id',
            'position_id' => 'nullable|exists:positions,id',
            'position_desc' => 'nullable|string|max:255',
            'rank_id' => 'nullable|exists:ranks,id',
            'npwp' => 'nullable|string|max:25',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_no' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'is_signer' => 'boolean',
            'is_non_staff' => 'boolean',
            'travel_grade_id' => 'nullable|exists:travel_grades,id',
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['password'] = bcrypt('password123'); // Default password
        
        // Convert empty strings to null for foreign key fields and date fields
        $nullableFields = ['unit_id', 'position_id', 'rank_id', 'travel_grade_id', 'birth_date'];
        foreach ($nullableFields as $key) {
            if (isset($validated[$key]) && ($validated[$key] === '' || $validated[$key] === null)) {
                $validated[$key] = null;
            }
        }
        
        User::create($validated);
        
        session()->flash('message', 'Data pegawai berhasil ditambahkan.');
        
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
        $travelGrades = TravelGrade::orderBy('name')->get();

        return view('livewire.users.create', compact('units', 'positions', 'ranks', 'travelGrades'));
    }
}
