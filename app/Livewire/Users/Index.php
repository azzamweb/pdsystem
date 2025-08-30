<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Data Pegawai'])]

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            session()->flash('message', 'Data pegawai berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data pegawai. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::with(['unit', 'position.echelon', 'rank', 'travelGrade'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('nip', 'like', '%' . $this->search . '%')
                      ->orWhere('nik', 'like', '%' . $this->search . '%');
                });
            })
            ->leftJoin('positions', 'users.position_id', '=', 'positions.id')
            ->leftJoin('echelons', 'positions.echelon_id', '=', 'echelons.id')
            ->leftJoin('ranks', 'users.rank_id', '=', 'ranks.id')
            // 1. Sort by eselon (lower number = higher eselon)
            ->orderByRaw('CASE WHEN echelons.id IS NULL THEN 999999 ELSE echelons.id END ASC')
            // 2. Sort by rank (higher number = higher rank)
            ->orderByRaw('CASE WHEN ranks.id IS NULL THEN 0 ELSE ranks.id END DESC')
            // 3. Sort by NIP (alphabetical)
            ->orderBy('users.nip', 'ASC')
            ->select('users.*')
            ->paginate(10);

        return view('livewire.users.index', compact('users'));
    }
}