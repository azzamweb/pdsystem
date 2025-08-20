<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Nota Dinas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Form pengubahan Nota Dinas</p>
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
            <div class="grid grid-cols-1>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Nota Dinas</label>
                    <div class="flex items-center space-x-2 mt-1">
                        <input type="checkbox" wire:model="number_is_manual" id="number_is_manual" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                        <span class="text-sm text-gray-700 dark:text-gray-300">Override nomor manual</span>
                        <input type="text" wire:model="manual_doc_no" id="manual_doc_no" class="ml-4 block w-64 border border-gray-300 rounded-md px-3 py-2 font-mono text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :disabled="!number_is_manual" />
                    </div>
                    @if($number_is_manual)
                        <input type="text" wire:model="number_manual_reason" placeholder="Alasan override nomor" class="mt-2 block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        @error('number_manual_reason')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    @endif
                </div>
                @include('livewire.nota-dinas._form-fields')
            </div>
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
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
