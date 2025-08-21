<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Generate SPPD</h1>
            @if($spt)
                <p class="text-sm text-gray-600 dark:text-gray-300">Berdasarkan SPT: <span class="font-mono">{{ $spt->doc_no }}</span></p>
            @endif
        </div>
        <a href="{{ $spt ? route('spt.show', $spt) : route('spt.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Kembali</a>
    </div>

    @if (session('error'))
        <div class="p-3 rounded-md bg-red-100 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif
    @if (session('message'))
        <div class="p-3 rounded-md bg-green-100 text-green-800 border border-green-200">{{ session('message') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit="save" class="space-y-6 p-6">
            @if($format_string)
                <div class="rounded-md border border-gray-200 dark:border-gray-700 p-3 bg-gray-50 dark:bg-gray-700/30 text-xs text-gray-700 dark:text-gray-300">
                    <div>Format aktif: <code class="px-1 py-0.5 bg-white/70 dark:bg-black/30 rounded">{{ $format_string }}</code></div>
                    <div>Contoh nomor berikutnya: <span class="font-mono">{{ $format_example }}</span></div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal SPPD <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="sppd_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('sppd_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Moda Transportasi <span class="text-red-500">*</span></label>
                    <div class="border rounded-md p-2 max-h-44 overflow-y-auto">
                        @foreach($transportModes as $tm)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" value="{{ $tm->id }}" wire:model="transport_mode_ids" />
                                <span>{{ $tm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('transport_mode_ids')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Berangkat (Origin) <span class="text-red-500">*</span></label>
                    <select wire:model="origin_place_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih tempat</option>
                        @foreach($orgPlaces as $pl)
                            <option value="{{ $pl->id }}">{{ $pl->name }}</option>
                        @endforeach
                    </select>
                    @error('origin_place_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Perjalanan <span class="text-red-500">*</span></label>
                    <select wire:model="trip_type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="LUAR_DAERAH">Luar Daerah</option>
                        <option value="DALAM_DAERAH_GT8H">Dalam Daerah > 8 Jam</option>
                        <option value="DALAM_DAERAH_LE8H">Dalam Daerah <= 8 Jam</option>
                        <option value="DIKLAT">Diklat</option>
                    </select>
                    @error('trip_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lama Perjalanan (hari) <span class="text-red-500">*</span></label>
                    <input type="number" min="1" wire:model="days_count" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('days_count')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div> --}}
                
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Pegawai yang Dibuatkan SPPD <span class="text-red-500">*</span></label>
                <div class="mb-2 flex items-center gap-3">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" x-on:change="$dispatch('select-all', { checked: $event.target.checked })" />
                        <span>Pilih semua</span>
                    </label>
                </div>
                <div class="border rounded-md max-h-56 overflow-y-auto p-2">
                    @foreach($participants as $p)
                        <label class="flex items-center gap-3 py-1">
                            <input type="checkbox" value="{{ $p['id'] }}" wire:model="selected_user_ids" />
                            <span>{{ $p['name'] }} <span class="text-xs text-gray-500">NIP {{ $p['nip'] }}</span></span>
                        </label>
                    @endforeach
                </div>
                @error('selected_user_ids')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ $spt ? route('spt.show', $spt) : route('spt.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Generate SPPD</button>
            </div>
        </form>
    </div>
</div>
