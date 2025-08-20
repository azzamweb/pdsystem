<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Nota Dinas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Form pembuatan Nota Dinas baru</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('nota-dinas.index') }}"
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
                            <th class="px-2 py-1 text-left">Perihal</th>
                            <th class="px-2 py-1 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->overlapDetails as $ov)
                            <tr>
                                <td class="px-2 py-1">{{ $ov['user'] }}</td>
                                <td class="px-2 py-1 font-mono">{{ $ov['doc_no'] }}</td>
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
                <a href="{{ route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Batal</a>
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
                let html = `<div class='overflow-x-auto'><table class='min-w-full text-xs'><thead><tr><th class='px-2 py-1 text-left'>Pegawai</th><th class='px-2 py-1 text-left'>Nomor ND</th><th class='px-2 py-1 text-left'>Perihal</th><th class='px-2 py-1 text-left'>Tanggal</th></tr></thead><tbody>`;
                details.forEach(ov => {
                    html += `<tr><td class='px-2 py-1'>${ov['user']}</td><td class='px-2 py-1 font-mono'>${ov['doc_no']}</td><td class='px-2 py-1'>${ov['hal']}</td><td class='px-2 py-1'>${ov['start_date'] ? moment(ov['start_date']).format('DD/MM/YYYY') : '-'} - ${ov['end_date'] ? moment(ov['end_date']).format('DD/MM/YYYY') : '-'}</td></tr>`;
                });
                html += '</tbody></table></div>';
                Swal.fire({
                    icon: 'warning',
                    title: 'Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain',
                    html: html,
                    confirmButtonText: 'Tutup',
                    width: 600
                });
            });
        });
    </script>
</div>
