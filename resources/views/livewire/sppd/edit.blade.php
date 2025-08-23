<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Surat Perintah Perjalanan Dinas (SPPD)</h1>
            <p class="text-gray-600 dark:text-gray-400">Nomor: <span class="font-mono">{{ $sppd->doc_no ?? 'N/A' }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('documents', ['nota_dinas_id' => $sppd->spt->nota_dinas_id, 'spt_id' => $sppd->spt_id, 'sppd_id' => $sppd->id]) }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">Kembali</a>
        </div>
    </div>

    @if (session('message'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form wire:submit="save">
            <!-- Informasi Dasar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pegawai
                    </label>
                    <input type="text" value="{{ $user_name }}" disabled 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        SPT
                    </label>
                    <input type="text" value="{{ $spt_info }}" disabled 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                </div>
            </div>

            <!-- Tanggal SPPD -->
            <div class="mb-6">
                <label for="sppd_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Tanggal SPPD <span class="text-red-500">*</span>
                </label>
                <input type="date" id="sppd_date" wire:model="sppd_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('sppd_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tempat Asal dan Tujuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="origin_place_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tempat Asal <span class="text-red-500">*</span>
                    </label>
                    <select id="origin_place_id" wire:model="origin_place_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Tempat Asal</option>
                        @foreach($orgPlaces as $place)
                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                        @endforeach
                    </select>
                    @error('origin_place_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="destination_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kota Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select id="destination_city_id" wire:model="destination_city_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Kota Tujuan</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('destination_city_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Moda Transportasi dan Jenis Perjalanan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="transport_mode_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Moda Transportasi <span class="text-red-500">*</span>
                    </label>
                    <div class="border rounded-md p-2 max-h-44 overflow-y-auto">
                        @foreach($transportModes as $mode)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" value="{{ $mode->id }}" wire:model="transport_mode_ids" />
                                <span>{{ $mode->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('transport_mode_ids') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="trip_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Perjalanan <span class="text-red-500">*</span>
                    </label>
                    <select id="trip_type" wire:model="trip_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="LUAR_DAERAH">Luar Daerah</option>
                        <option value="DALAM_DAERAH_GT8H">Dalam Daerah > 8 Jam</option>
                        <option value="DALAM_DAERAH_LE8H">Dalam Daerah ≤ 8 Jam</option>
                        <option value="DIKLAT">Diklat</option>
                    </select>
                    @error('trip_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>



            <!-- Sumber Dana -->
            <div class="mb-6">
                <label for="funding_source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Sumber Dana
                </label>
                <input type="text" id="funding_source" wire:model="funding_source" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Contoh: APBD, APBN, dll">
                @error('funding_source') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('documents', ['nota_dinas_id' => $sppd->spt->nota_dinas_id, 'spt_id' => $sppd->spt_id, 'sppd_id' => $sppd->id]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
