<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="requesting_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bidang Pengaju <span class="text-red-500">*</span></label>
        <select wire:model="requesting_unit_id" id="requesting_unit_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
            @endforeach
        </select>
        @error('requesting_unit_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="to_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kepada (Kepala OPD/Eselon II) <span class="text-red-500">*</span></label>
        <select wire:model="to_user_id" id="to_user_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Pegawai</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->fullNameWithTitles() }} ({{ trim(($user->position?->name ?? '') . ' ' . ($user->unit?->name ?? '')) ?: '-' }})</option>
            @endforeach
        </select>
        @error('to_user_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="from_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari (Kepala Bidang/Eselon III) <span class="text-red-500">*</span></label>
        <select wire:model="from_user_id" id="from_user_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Pegawai</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->fullNameWithTitles() }} ({{ trim(($user->position?->name ?? '') . ' ' . ($user->unit?->name ?? '')) ?: '-' }})</option>
            @endforeach
        </select>
        @error('from_user_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="nd_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Nota Dinas <span class="text-red-500">*</span></label>
        <input type="date" wire:model="nd_date" id="nd_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        @error('nd_date')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="origin_place_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Asal <span class="text-red-500">*</span></label>
        <select wire:model="origin_place_id" id="origin_place_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Tempat Asal</option>
            @foreach($orgPlaces as $orgPlace)
                <option value="{{ $orgPlace->id }}">{{ $orgPlace->name }}</option>
            @endforeach
        </select>
        @error('origin_place_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="destination_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kota/Kab Tujuan <span class="text-red-500">*</span></label>
        <select wire:model="destination_city_id" id="destination_city_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Kota/Kabupaten</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }} - {{ $city->province->name ?? 'N/A' }}</option>
            @endforeach
        </select>
        @error('destination_city_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai <span class="text-red-500">*</span></label>
        <input type="date" wire:model="start_date" id="start_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        @error('start_date')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kembali <span class="text-red-500">*</span></label>
        <input type="date" wire:model="end_date" id="end_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        @error('end_date')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="lampiran_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Lampiran <span class="text-red-500">*</span></label>
        <input type="number" wire:model="lampiran_count" id="lampiran_count" min="1" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        @error('lampiran_count')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="hal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Perihal <span class="text-red-500">*</span></label>
        <input type="text" wire:model="hal" id="hal" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        @error('hal')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="dasar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dasar <span class="text-red-500">*</span></label>
        <textarea wire:model="dasar" id="dasar" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
        @error('dasar')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="maksud" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maksud <span class="text-red-500">*</span></label>
        <textarea wire:model="maksud" id="maksud" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
        @error('maksud')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="tembusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tembusan</label>
        <textarea wire:model="tembusan" id="tembusan" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
        @error('tembusan')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="sifat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sifat</label>
        <select wire:model="sifat" id="sifat" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="Penting">Penting</option>
            <option value="Segera">Segera</option>
            <option value="Biasa">Biasa</option>
            <option value="Rahasia">Rahasia</option>
        </select>
        @error('sifat')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peserta (Pegawai yang bepergian) <span class="text-red-500">*</span></label>
        <div x-data="{ q: '', selected: @entangle('participants') }" class="mt-1">
            <input type="text" x-model="q" placeholder="Cari nama / jabatan / unit..." class="mb-2 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
            <div class="overflow-y-auto overscroll-y-contain border border-gray-200 dark:border-gray-700 rounded-md divide-y divide-gray-100 dark:divide-gray-700" x-ref="list" style="height:200px;">
                @foreach($users as $user)
                    @php
                        $label = $user->fullNameWithTitles() . ' (' . trim(($user->position->name ?? '') . ' ' . ($user->unit->name ?? '')) . ')';
                    @endphp
                    <label class="flex items-center gap-2 px-3 py-2 text-sm" x-bind:data-text="'{{ strtolower($label) }}'" x-show="!q || ($el.dataset.text || '').includes(q.toLowerCase())" x-cloak>
                        <input type="checkbox" value="{{ $user->id }}" x-model="selected" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                        <span class="text-gray-900 dark:text-gray-100">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <div class="mt-2 flex items-center gap-2">
                <button type="button" class="px-3 py-1.5 text-xs rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100" @click="selected = [];">Kosongkan</button>
                <button type="button" class="px-3 py-1.5 text-xs rounded-md bg-blue-600 hover:bg-blue-700 text-white" @click="selected = Array.from(new Set([...
                    selected,
                    ...Array.from($refs.list.querySelectorAll('label'))
                        .filter(el => el.style.display !== 'none')
                        .map(el => el.querySelector('input[type=checkbox]')?.value)
                        .filter(Boolean)
                ])).map(v => isNaN(v) ? v : Number(v))">Pilih yang tampil</button>
                <span class="text-xs text-gray-500 dark:text-gray-400">Dipilih: <span x-text="selected.length"></span> pegawai</span>
            </div>
        </div>
        @error('participants')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
        <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
        @error('notes')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
</div>
