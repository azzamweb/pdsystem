<?php

namespace App\Livewire\IntraDistrictTransportRefs;

use App\Models\IntraDistrictTransportRef;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Data Referensi Transportasi Dalam Kecamatan'])]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(IntraDistrictTransportRef $transportRef)
    {
        try {
            $transportRef->delete();
            session()->flash('message', 'Referensi transportasi dalam kecamatan berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus referensi transportasi. ' . $e->getMessage());
        }
    }

    public function render()
    {
        $transportRefs = IntraDistrictTransportRef::query()
            ->with(['originPlace.city.province', 'destinationDistrict.city.province'])
            ->whereHas('originPlace.city.province') // Pastikan originPlace dan city.province ada
            ->whereHas('destinationDistrict.city.province') // Pastikan destinationDistrict dan city.province ada
            ->when($this->search, function ($query) {
                $query->whereHas('originPlace', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('destinationDistrict', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('kemendagri_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.intra-district-transport-refs.index', [
            'transportRefs' => $transportRefs
        ]);
    }
}
