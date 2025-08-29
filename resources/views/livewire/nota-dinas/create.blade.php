<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Nota Dinas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Form pembuatan Nota Dinas baru</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('documents') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->has('general'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
            {{ $errors->first('general') }}
        </div>
    @endif
    @if ($errors->any() && !$errors->has('general'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        @if (!empty($this->overlapDetails))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-800 rounded-lg dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-200">
            <div class="font-semibold mb-2">Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain:</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 text-left">Pegawai</th>
                            <th class="px-2 py-1 text-left">Nomor ND</th>
                            <th class="px-2 py-1 text-left">Bidang</th>
                            <th class="px-2 py-1 text-left">Perihal</th>
                            <th class="px-2 py-1 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->overlapDetails as $ov)
                            <tr>
                                <td class="px-2 py-1">{{ $ov['user'] }}</td>
                                <td class="px-2 py-1 font-mono">{{ $ov['doc_no'] }}</td>
                                <td class="px-2 py-1">{{ $ov['unit'] ?? '-' }}</td>
                                <td class="px-2 py-1">{{ $ov['hal'] }}</td>
                                <td class="px-2 py-1">{{ $ov['start_date'] ? \Carbon\Carbon::parse($ov['start_date'])->format('d/m/Y') : '-' }} - {{ $ov['end_date'] ? \Carbon\Carbon::parse($ov['end_date'])->format('d/m/Y') : '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit="save" class="space-y-6 p-6">
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
                    <div class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" wire:model.live="use_custom_signer_title" id="use_custom_signer_title" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                        <label for="use_custom_signer_title" class="text-sm font-medium text-gray-700 dark:text-gray-300">Gunakan judul penandatangan custom</label>
                    </div>
                    @if($use_custom_signer_title)
                        <label for="custom_signer_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Penandatangan Custom</label>
                        <textarea wire:model="custom_signer_title" id="custom_signer_title" rows="2" placeholder="Contoh: Kepala Dinas Pendidikan, Pemuda dan Olahraga&#10;atau&#10;Sekretaris Daerah&#10;Kabupaten Bengkalis" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        @error('custom_signer_title')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    @else
                        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Judul normal:</span> Akan menggunakan judul berdasarkan jabatan dan unit pegawai yang dipilih sebagai penandatangan.
                            </p>
                        </div>
                    @endif
                </div>
                <div>
                    <label for="origin_place_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Asal <span class="text-red-500">*</span></label>
                    <select wire:model="origin_place_id" id="origin_place_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Tempat Asal</option>
                        @foreach(\App\Models\OrgPlace::orderBy('name')->get() as $place)
                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                        @endforeach
                    </select>
                    @error('origin_place_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="destination_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kota/Kab Tujuan <span class="text-red-500">*</span></label>
                    <select wire:model="destination_city_id" id="destination_city_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Pilih Kota/Kab</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('destination_city_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="trip_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Perjalanan <span class="text-red-500">*</span></label>
                    <select wire:model="trip_type" id="trip_type" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="LUAR_DAERAH">Luar Daerah</option>
                        <option value="DALAM_DAERAH_GT8H">Dalam Daerah > 8 Jam</option>
                        <option value="DALAM_DAERAH_LE8H">Dalam Daerah ≤ 8 Jam</option>
                        <option value="DIKLAT">Diklat</option>
                    </select>
                    @error('trip_type')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peserta (Pegawai yang bepergian) <span class="text-red-500">*</span></label>
                    <div class="mt-1">
                        <input type="text" id="search-participants" placeholder="Cari nama / jabatan / unit..." class="mb-2 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        <div class="overflow-y-auto overscroll-y-contain border border-gray-200 dark:border-gray-700 rounded-md divide-y divide-gray-100 dark:divide-gray-700" style="height:250px;">
                            @if($users->count() > 0)
                                @foreach($users as $user)
                                @php
                                    $label = $user->fullNameWithTitles() . ' (' . trim(($user->position?->name ?? '') . ' ' . ($user->unit?->name ?? '')) . ')';
                                @endphp
                                <label class="flex items-start gap-3 px-4 py-3 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 participant-item cursor-pointer" data-text="{{ strtolower($label) }}">
                                    <input type="checkbox" name="participants[]" value="{{ $user->id }}" wire:model="participants" class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->fullNameWithTitles() }}
                                        </div>
                                        @if($user->position?->name || $user->unit?->name)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                @if($user->position?->name)
                                                    <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded mr-2">
                                                        {{ $user->position->name }}
                                                    </span>
                                                @endif
                                                @if($user->unit?->name)
                                                    <span class="inline-block bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded">
                                                        {{ $user->unit->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($user->nip)
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                NIP: {{ $user->nip }}
                                            </div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            @else
                                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada data pegawai</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data pegawai yang tersedia.
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <button type="button" class="px-3 py-1.5 text-xs rounded-md bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100" onclick="clearParticipants()">Kosongkan</button>
                            <button type="button" class="px-3 py-1.5 text-xs rounded-md bg-blue-600 hover:bg-blue-700 text-white" onclick="selectVisibleParticipants()">Pilih yang tampil</button>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Dipilih: <span id="selected-count">{{ count($participants) }}</span> pegawai</span>
                        </div>
                    </div>
                    @error('participants')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                    <textarea wire:model="notes" id="notes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                    @error('notes')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="doc_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Nota Dinas</label>
                    <div class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" wire:model="number_is_manual" id="number_is_manual" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                        <span class="text-sm text-gray-700 dark:text-gray-300">Input manual</span>
                        <input type="text" wire:model="doc_no" id="doc_no" class="ml-4 block w-64 border border-gray-300 rounded-md px-3 py-2 font-mono text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :readonly="!number_is_manual" />
                    </div>
                    @if($number_is_manual)
                        <input type="text" wire:model="number_manual_reason" placeholder="Alasan override nomor" class="mt-2 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('number_manual_reason')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('documents') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('showOverlapAlert', (details) => {
                console.log('Overlap Details received:', details);
                
                let html = `<div class='overflow-x-auto'><table class='min-w-full text-xs'><thead><tr><th class='px-2 py-1 text-left'>Pegawai</th><th class='px-2 py-1 text-left'>Nomor ND</th><th class='px-2 py-1 text-left'>Bidang</th><th class='px-2 py-1 text-left'>Perihal</th><th class='px-2 py-1 text-left'>Tanggal</th></tr></thead><tbody>`;
                
                if (Array.isArray(details)) {
                    details.forEach(ov => {
                        const user = ov.user || 'N/A';
                        const docNo = ov.doc_no || 'N/A';
                        const unit = ov.unit || '-';
                        const hal = ov.hal || 'N/A';
                        const startDate = ov.start_date ? moment(ov.start_date).format('DD/MM/YYYY') : '-';
                        const endDate = ov.end_date ? moment(ov.end_date).format('DD/MM/YYYY') : '-';
                        
                        // Debug: Log individual values
                        console.log('Processing overlap:', {
                            user: user,
                            docNo: docNo,
                            unit: unit,
                            hal: hal,
                            startDate: startDate,
                            endDate: endDate,
                            rawStartDate: ov.start_date,
                            rawEndDate: ov.end_date
                        });
                        
                        html += `<tr>
                            <td class='px-2 py-1'>${user}</td>
                            <td class='px-2 py-1 font-mono'>${docNo}</td>
                            <td class='px-2 py-1'>${unit}</td>
                            <td class='px-2 py-1'>${hal}</td>
                            <td class='px-2 py-1'>${startDate} - ${endDate}</td>
                        </tr>`;
                    });
                } else {
                    html += `<tr><td colspan='5' class='px-2 py-1 text-center text-red-500'>Data tidak valid</td></tr>`;
                }
                
                html += '</tbody></table></div>';
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain',
                    html: html,
                    confirmButtonText: 'Tutup',
                    width: 800
                });
            });
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-participants');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const participantItems = document.querySelectorAll('.participant-item');
                    
                    participantItems.forEach(item => {
                        const text = item.getAttribute('data-text');
                        if (text.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });

        // Clear all participants
        function clearParticipants() {
            const checkboxes = document.querySelectorAll('input[name="participants[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            // Trigger Livewire update
            @this.set('participants', []);
            updateSelectedCount();
        }

        // Select visible participants
        function selectVisibleParticipants() {
            const visibleItems = document.querySelectorAll('.participant-item');
            const checkboxes = [];
            
            visibleItems.forEach(item => {
                if (item.style.display !== 'none') {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = true;
                        checkboxes.push(checkbox.value);
                    }
                }
            });
            
            // Trigger Livewire update
            @this.set('participants', checkboxes);
            updateSelectedCount();
        }

        // Update selected count
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('input[name="participants[]"]:checked');
            const countElement = document.getElementById('selected-count');
            if (countElement) {
                countElement.textContent = checkboxes.length;
            }
        }

        // Listen for checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.name === 'participants[]') {
                updateSelectedCount();
            }
        });
    </script>
</div>
