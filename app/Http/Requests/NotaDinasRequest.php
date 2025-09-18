<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotaDinasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'requesting_unit_id' => 'required|exists:units,id',
            'to_user_id' => 'required|exists:users,id',
            'from_user_id' => 'required|exists:users,id',
            'destination_city_id' => 'required|exists:cities,id',
            'origin_place_id' => 'required|exists:org_places,id',
            'sifat' => 'required|in:Penting,Segera,Biasa,Rahasia',
            'nd_date' => 'required|date',
            'hal' => 'required|string|max:255',
            'custom_signer_title' => 'nullable|string|max:255',
            'dasar' => 'required|string',
            'maksud' => 'required|string',
            'lampiran_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'trip_type' => 'required|in:LUAR_DAERAH,DALAM_DAERAH_GT8H,DALAM_DAERAH_LE8H,DIKLAT',
            'status' => 'required|in:DRAFT,APPROVED',
            'tembusan' => 'nullable|string',
            'notes' => 'nullable|string',
        ];

        // Handle manual document number validation
        if ($this->boolean('number_is_manual')) {
            $rules['doc_no'] = 'required|string|unique:nota_dinas,doc_no';
            $rules['number_manual_reason'] = 'required|string|max:255';
        } else {
            $rules['doc_no'] = 'nullable|string';
            $rules['number_manual_reason'] = 'nullable|string|max:255';
        }

        // For edit, exclude current record from unique validation
        if ($this->route('notaDinas')) {
            $notaDinasId = $this->route('notaDinas')->id;
            if ($this->boolean('number_is_manual')) {
                $rules['doc_no'] = 'required|string|unique:nota_dinas,doc_no,' . $notaDinasId;
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'requesting_unit_id.required' => 'Unit pemohon harus dipilih.',
            'requesting_unit_id.exists' => 'Unit pemohon tidak valid.',
            'to_user_id.required' => 'Kepada harus dipilih.',
            'to_user_id.exists' => 'Kepada tidak valid.',
            'from_user_id.required' => 'Dari harus dipilih.',
            'from_user_id.exists' => 'Dari tidak valid.',
            'destination_city_id.required' => 'Kota tujuan harus dipilih.',
            'destination_city_id.exists' => 'Kota tujuan tidak valid.',
            'origin_place_id.required' => 'Tempat asal harus dipilih.',
            'origin_place_id.exists' => 'Tempat asal tidak valid.',
            'sifat.required' => 'Sifat surat harus dipilih.',
            'sifat.in' => 'Sifat surat tidak valid.',
            'nd_date.required' => 'Tanggal nota dinas harus diisi.',
            'nd_date.date' => 'Format tanggal nota dinas tidak valid.',
            'hal.required' => 'Hal harus diisi.',
            'hal.max' => 'Hal maksimal 255 karakter.',
            'dasar.required' => 'Dasar harus diisi.',
            'maksud.required' => 'Maksud harus diisi.',
            'lampiran_count.required' => 'Jumlah lampiran harus diisi.',
            'lampiran_count.integer' => 'Jumlah lampiran harus berupa angka.',
            'lampiran_count.min' => 'Jumlah lampiran minimal 1.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal selesai harus diisi.',
            'end_date.date' => 'Format tanggal selesai tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'participants.required' => 'Peserta harus dipilih.',
            'participants.array' => 'Peserta harus berupa array.',
            'participants.min' => 'Minimal 1 peserta harus dipilih.',
            'participants.*.exists' => 'Salah satu peserta tidak valid.',
            'trip_type.required' => 'Jenis perjalanan harus dipilih.',
            'trip_type.in' => 'Jenis perjalanan tidak valid.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
            'doc_no.required' => 'Nomor dokumen harus diisi.',
            'doc_no.unique' => 'Nomor dokumen sudah digunakan.',
            'number_manual_reason.required' => 'Alasan nomor manual harus diisi.',
            'number_manual_reason.max' => 'Alasan nomor manual maksimal 255 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'requesting_unit_id' => 'Unit Pemohon',
            'to_user_id' => 'Kepada',
            'from_user_id' => 'Dari',
            'destination_city_id' => 'Kota Tujuan',
            'origin_place_id' => 'Tempat Asal',
            'sifat' => 'Sifat Surat',
            'nd_date' => 'Tanggal Nota Dinas',
            'hal' => 'Hal',
            'custom_signer_title' => 'Jabatan Penandatangan Kustom',
            'dasar' => 'Dasar',
            'maksud' => 'Maksud',
            'lampiran_count' => 'Jumlah Lampiran',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Selesai',
            'participants' => 'Peserta',
            'trip_type' => 'Jenis Perjalanan',
            'status' => 'Status',
            'tembusan' => 'Tembusan',
            'notes' => 'Catatan',
            'doc_no' => 'Nomor Dokumen',
            'number_manual_reason' => 'Alasan Nomor Manual',
        ];
    }
}
