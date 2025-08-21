<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Surat Perintah Tugas (SPT)</h1>
            @if($notaDinas)
                <p class="text-sm text-gray-600 dark:text-gray-300">Berdasarkan Nota Dinas: <span class="font-mono">{{ $notaDinas->doc_no }}</span></p>
            @endif
        </div>
        <a href="{{ $notaDinas ? route('nota-dinas.show', $notaDinas->id) : route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            Kembali ke Nota Dinas
        </a>
    </div>

    @if (session('error'))
        <div class="p-3 rounded-md bg-red-100 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit="save" class="space-y-6 p-6">
            <!-- Hanya Tanggal SPT -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal SPT <span class="text-red-500">*</span></label>
                <input type="date" wire:model="spt_date" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                @error('spt_date')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Penandatangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penandatangan <span class="text-red-500">*</span></label>
                <select wire:model="signed_by_user_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih penandatangan</option>
                    @foreach($signers as $sg)
                        <option value="{{ $sg->id }}">{{ $sg->fullNameWithTitles() }} ({{ trim(($sg->position?->name ?? '') . ' ' . ($sg->unit?->name ?? '')) ?: '-' }})</option>
                    @endforeach
                </select>
                @error('signed_by_user_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Assignment Title (opsional, auto-fallback) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan pada SPT (Assignment Title)</label>
                <input type="text" wire:model="assignment_title" placeholder="Kosongkan untuk otomatis pakai position_desc / nama jabatan penandatangan" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                @error('assignment_title')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Penomoran Dokumen -->
            <div class="rounded-md border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-semibold mb-3">Penomoran Dokumen</h3>
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" id="number_is_manual" wire:model.live="number_is_manual" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    <label for="number_is_manual" class="text-sm text-gray-700 dark:text-gray-300">Input nomor manual</label>
                </div>
                @if($number_is_manual)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor SPT (Manual)</label>
                            <input type="text" wire:model="manual_doc_no" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            @error('manual_doc_no')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penomoran Manual</label>
                            <input type="text" wire:model="number_manual_reason" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                            @error('number_manual_reason')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-gray-600 dark:text-gray-400">
                        <div>Format aktif: <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $format_string ?? '-' }}</code></div>
                        <div>Contoh: <span class="font-mono">{{ $format_example ?? '-' }}</span></div>
                    </div>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nomor akan dibuat otomatis menggunakan format SPT (scope global).</p>
                @endif
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ $notaDinas ? route('nota-dinas.show', $notaDinas->id) : route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan SPT</button>
            </div>
        </form>
    </div>
</div>
