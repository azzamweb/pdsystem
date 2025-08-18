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
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        @error('to_user_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="from_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari (Kepala Bidang/Eselon III) <span class="text-red-500">*</span></label>
        <select wire:model="from_user_id" id="from_user_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Pegawai</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
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
        <select wire:model="participants" id="participants" multiple class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih satu atau lebih pegawai yang akan bepergian</p>
        @error('participants')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
        <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
        @error('notes')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
</div>
