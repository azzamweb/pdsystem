<?php

namespace App\Livewire\IntraProvinceTransportRefs;

use App\Models\IntraProvinceTransportRef;
use App\Models\OrgPlace;
use App\Models\City;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    public IntraProvinceTransportRef $transportRef;
    public $origin_place_id = '';
    public $destination_city_id = '';
    public $pp_amount = '';
    public $valid_from = '';
    public $valid_to = '';
    public $source_ref = '';

    protected $rules = [
        'origin_place_id' => 'required|exists:org_places,id',
        'destination_city_id' => 'required|exists:cities,id',
        'pp_amount' => 'required|numeric|min:0',
        'valid_from' => 'required|date',
        'valid_to' => 'nullable|date|after:valid_from',
        'source_ref' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'origin_place_id.required' => 'Tempat asal wajib dipilih',
        'origin_place_id.exists' => 'Tempat asal yang dipilih tidak valid',
        'destination_city_id.required' => 'Kota tujuan wajib dipilih',
        'destination_city_id.exists' => 'Kota tujuan yang dipilih tidak valid',
        'pp_amount.required' => 'Tarif per orang wajib diisi',
        'pp_amount.numeric' => 'Tarif per orang harus berupa angka',
        'pp_amount.min' => 'Tarif per orang minimal 0',
        'valid_from.required' => 'Tanggal mulai berlaku wajib diisi',
        'valid_from.date' => 'Tanggal mulai berlaku harus berupa tanggal yang valid',
        'valid_to.date' => 'Tanggal berakhir berlaku harus berupa tanggal yang valid',
        'valid_to.after' => 'Tanggal berakhir berlaku harus setelah tanggal mulai berlaku',
        'source_ref.max' => 'Sumber referensi maksimal 255 karakter',
    ];

    public function mount(IntraProvinceTransportRef $transportRef)
    {
        $this->transportRef = $transportRef;
        $this->origin_place_id = $transportRef->origin_place_id;
        $this->destination_city_id = $transportRef->destination_city_id;
        $this->pp_amount = $transportRef->pp_amount;
        $this->valid_from = $transportRef->valid_from->format('Y-m-d');
        $this->valid_to = $transportRef->valid_to ? $transportRef->valid_to->format('Y-m-d') : '';
        $this->source_ref = $transportRef->source_ref;
    }

    public function update()
    {
        $this->validate();

        $this->transportRef->update([
            'origin_place_id' => $this->origin_place_id,
            'destination_city_id' => $this->destination_city_id,
            'pp_amount' => $this->pp_amount,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to ?: null,
            'source_ref' => $this->source_ref ?: null,
        ]);

        session()->flash('message', 'Referensi transportasi dalam provinsi berhasil diperbarui');
        return redirect()->route('intra-province-transport-refs.index');
    }

    public function render()
    {
        $orgPlaces = OrgPlace::with('city.province')
            ->whereHas('city.province') // Pastikan hanya org places yang memiliki city dan province
            ->orderBy('name')
            ->get();
        $cities = City::with('province')
            ->whereHas('province') // Pastikan hanya cities yang memiliki province
            ->orderBy('name')
            ->get();

        return view('livewire.intra-province-transport-refs.edit', compact('orgPlaces', 'cities'));
    }
}
