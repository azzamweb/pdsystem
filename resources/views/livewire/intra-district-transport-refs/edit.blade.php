<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Referensi Transportasi Dalam Kecamatan</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Edit referensi tarif transportasi dari tempat kerja ke ibukota kecamatan
                </p>
            </div>
            <a href="{{ route('reference-rates.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-flux::icon.chevrons-up-down class="w-4 h-4 mr-2" />
                Kembali ke Referensi Tarif
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg p-6">
            <form wire:submit="save">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Origin Place -->
                    <div>
                        <label for="origin_place_id" class="block text-sm font-medium text-gray-700">
                            Tempat Kerja <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="origin_place_id" id="origin_place_id" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Pilih Tempat Kerja</option>
                            @foreach($orgPlaces as $orgPlace)
                                <option value="{{ $orgPlace->id }}">
                                    {{ $orgPlace->name }} - {{ $orgPlace->city->name }}, {{ $orgPlace->city->province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('origin_place_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Destination District -->
                    <div>
                        <label for="destination_district_id" class="block text-sm font-medium text-gray-700">
                            Kecamatan Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="destination_district_id" id="destination_district_id" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">
                                    {{ $district->name }} ({{ $district->kemendagri_code }}) - {{ $district->city->name }}, {{ $district->city->province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_district_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PP Amount -->
                    <div class="md:col-span-2">
                        <label for="pp_amount" class="block text-sm font-medium text-gray-700">
                            Tarif Transportasi (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input wire:model="pp_amount" type="number" id="pp_amount" 
                                   class="pl-12 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                   placeholder="0">
                        </div>
                        @error('pp_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Masukkan tarif transportasi dalam rupiah (tanpa tanda koma atau titik)
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('reference-rates.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-flux::icon.book-open-text class="w-4 h-4 mr-2" />
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
