<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Nota Dinas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lihat detail lengkap Nota Dinas</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('nota-dinas.edit', $notaDinas) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900 dark:border-green-700 dark:text-green-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg dark:bg-red-900 dark:border-red-700 dark:text-red-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Status Badge -->
    <div class="flex items-center justify-center">
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $notaDinas->status === 'DRAFT' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : ($notaDinas->status === 'SUBMITTED' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($notaDinas->status === 'APPROVED' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @if($notaDinas->status === 'DRAFT')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                @elseif($notaDinas->status === 'SUBMITTED')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                @elseif($notaDinas->status === 'APPROVED')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                @endif
            </svg>
            {{ $notaDinas->status }}
        </span>
    </div>

    <!-- Status Update Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Update Status Dokumen
            </h3>
        </div>
        <div class="p-6">
            <form wire:submit="updateStatus" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Baru
                        </label>
                        <select wire:model="new_status" id="new_status" class="block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Pilih status...</option>
                            @if($notaDinas->status === 'DRAFT')
                                <option value="SUBMITTED">SUBMITTED - Ajukan untuk review</option>
                            @elseif($notaDinas->status === 'SUBMITTED')
                                <option value="APPROVED">APPROVED - Setujui dokumen</option>
                                <option value="REJECTED">REJECTED - Tolak dokumen</option>
                            @elseif($notaDinas->status === 'APPROVED')
                                <option value="DRAFT">DRAFT - Kembalikan ke draft</option>
                            @elseif($notaDinas->status === 'REJECTED')
                                <option value="DRAFT">DRAFT - Kembalikan ke draft</option>
                                <option value="SUBMITTED">SUBMITTED - Ajukan ulang</option>
                            @endif
                        </select>
                        @error('new_status') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Status (Opsional)
                        </label>
                        <textarea wire:model="status_notes" id="status_notes" rows="2" placeholder="Tambahkan catatan untuk perubahan status..." class="block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        @error('status_notes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                @if($new_status)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Aksi yang akan dilakukan:
                                </h4>
                                <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                                    @if($new_status === 'SUBMITTED')
                                        <li>• Dokumen akan diajukan untuk review</li>
                                        <li>• Status berubah dari DRAFT ke SUBMITTED</li>
                                        <li>• Dokumen siap untuk proses approval</li>
                                    @elseif($new_status === 'APPROVED')
                                        <li>• Dokumen disetujui</li>
                                        <li>• Status berubah dari SUBMITTED ke APPROVED</li>
                                        <li>• Dapat dilanjutkan ke pembuatan SPT/SPPD</li>
                                    @elseif($new_status === 'REJECTED')
                                        <li>• Dokumen ditolak</li>
                                        <li>• Status berubah dari SUBMITTED ke REJECTED</li>
                                        <li>• Perlu perbaikan sebelum diajukan ulang</li>
                                    @elseif($new_status === 'DRAFT')
                                        <li>• Dokumen dikembalikan ke status draft</li>
                                        <li>• Dapat diedit kembali</li>
                                        <li>• Perlu diajukan ulang setelah perbaikan</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="resetStatusForm" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        Reset
                    </button>
                    <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">
                        <svg wire:loading wire:target="updateStatus" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Next Document Actions -->
    @if($notaDinas->status === 'APPROVED')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <h4 class="text-lg font-medium text-green-800 dark:text-green-200 mb-2">
                        Nota Dinas Telah Disetujui
                    </h4>
                    <p class="text-sm text-green-700 dark:text-green-300 mb-4">
                        Nota Dinas ini telah disetujui dan siap untuk dilanjutkan ke tahap berikutnya. Anda dapat membuat dokumen SPT (Surat Perintah Tugas) berdasarkan Nota Dinas ini.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('spt.create', ['nota_dinas_id' => $notaDinas->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat SPT
                        </a>
                        <a href="{{ route('sppd.create', ['nota_dinas_id' => $notaDinas->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Buat SPPD
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">

             <!-- Organization Info -->
             <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Informasi Organisasi
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bidang Pengaju</label>
                        <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->requestingUnit?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kepada</label>
                        <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->toUser?->name ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dari</label>
                        <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->fromUser?->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Document Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi Dokumen
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Nota Dinas</label>
                            <div class="mt-1 font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $notaDinas->doc_no }}</div>
                            @if($notaDinas->number_is_manual)
                                <div class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Override manual: {{ $notaDinas->number_manual_reason }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Nota Dinas</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->nd_date ? \Carbon\Carbon::parse($notaDinas->nd_date)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Sifat</label>
                            <div class="mt-1">
                                @if($notaDinas->sifat)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $notaDinas->sifat }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Lampiran</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->lampiran_count }} lembar</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Perihal</label>
                        <div class="mt-1 text-gray-900 dark:text-white font-medium">{{ $notaDinas->hal }}</div>
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Informasi Perjalanan
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tujuan</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->destinationCity?->name ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Durasi Perjalanan</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->days_count }} hari</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->start_date ? \Carbon\Carbon::parse($notaDinas->start_date)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</label>
                            <div class="mt-1 text-gray-900 dark:text-white">{{ $notaDinas->end_date ? \Carbon\Carbon::parse($notaDinas->end_date)->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Isi Nota Dinas
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dasar</label>
                        <div class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $notaDinas->dasar }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Maksud</label>
                        <div class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $notaDinas->maksud }}</div>
                    </div>
                    @if($notaDinas->tembusan)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tembusan</label>
                            <div class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $notaDinas->tembusan }}</div>
                        </div>
                    @endif
                    @if($notaDinas->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</label>
                            <div class="mt-1 text-gray-900 dark:text-white whitespace-pre-line">{{ $notaDinas->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Participants -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Peserta ({{ $notaDinas->participants->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    @if($notaDinas->participants->count() > 0)
                        <div class="space-y-3">
                            @foreach($notaDinas->participants as $participant)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">
                                                {{ strtoupper(substr($participant->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $participant->user->name ?? 'N/A' }}
                                        </p>
                                        @if($participant->user->position)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                {{ $participant->user->position->name ?? '-' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada peserta</p>
                        </div>
                    @endif
                </div>
            </div>

           

            <!-- Document Numbering Info -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Informasi Penomoran
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Format</label>
                        <div class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $notaDinas->numberFormat?->format_string ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Sequence ID</label>
                        <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $notaDinas->number_sequence_id ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Scope Unit</label>
                        <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $notaDinas->number_scope_unit_id ?? '-' }}</div>
                    </div>
                    @if($notaDinas->number_is_manual)
                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span class="text-xs text-yellow-800 dark:text-yellow-200 font-medium">Override Manual</span>
                            </div>
                            <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-300">{{ $notaDinas->number_manual_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
