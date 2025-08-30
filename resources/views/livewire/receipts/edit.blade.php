<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ $this->getBackUrl() }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Dokumen
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Kwitansi</h1>
                </div>
            </div>

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded dark:bg-green-900 dark:border-green-700 dark:text-green-300">
                    {{ session('message') }}
                </div>
            @endif

            <!-- SPPD Information -->
            @if($sppd)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-3">Informasi SPPD</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Nomor SPPD:</span>
                        <p class="text-blue-900 dark:text-blue-100 font-mono">{{ $sppd->doc_no }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Pegawai:</span>
                        <p class="text-blue-900 dark:text-blue-100">{{ $sppd->user->fullNameWithTitles() }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Perjalanan:</span>
                                                        <p class="text-blue-900 dark:text-blue-100">{{ $sppd->spt?->notaDinas?->originPlace?->name ?? 'N/A' }} → {{ $sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Jenis Perjalanan:</span>
                        <p class="text-blue-900 dark:text-blue-100">
                            @switch($sppd->trip_type)
                                @case('LUAR_DAERAH')
                                    Luar Daerah
                                    @break
                                @case('DALAM_DAERAH_GT8H')
                                    Dalam Daerah > 8 Jam
                                    @break
                                @case('DALAM_DAERAH_LE8H')
                                    Dalam Daerah ≤ 8 Jam
                                    @break
                                @case('DIKLAT')
                                    Diklat
                                    @break
                                @default
                                    {{ $sppd->trip_type }}
                            @endswitch
                        </p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Jumlah Hari:</span>
                        <p class="text-blue-900 dark:text-blue-100">{{ $sppd->days_count }} Hari</p>
                    </div>
                    <div class="space-y-1">
                        <span class="font-medium text-blue-700 dark:text-blue-300">Status:</span>
                        <p class="text-blue-900 dark:text-blue-100">{{ $sppd->status }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-6">
                    <form wire:submit="save" class="space-y-6">
                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Kwitansi *
                                </label>
                                <input 
                                    type="date" 
                                    wire:model="receipt_date" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                />
                                @error('receipt_date') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Kwitansi (SIPD)
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="manual_doc_no" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Nomor dari aplikasi SIPD (opsional)"
                                />
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Nomor kwitansi akan diisi dari aplikasi SIPD. Bisa dikosongkan untuk sementara.
                                </div>
                                @error('manual_doc_no') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tingkatan Perjalanan
                            </label>
                            <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    @if($sppd->user->travelGrade)
                                        {{ $sppd->user->travelGrade->name }}
                                    @else
                                        <span class="text-red-500">Tingkatan perjalanan belum ditentukan</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Diambil dari data pegawai
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Penerima Pembayaran
                            </label>
                            <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $sppd->user->fullNameWithTitles() }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    NIP: {{ $sppd->user->nip }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Total Amount
                            </label>
                            <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white font-mono text-lg font-bold">
                                Rp {{ number_format($this->getTotalAmount(), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Transportation Section -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">I. Transportasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Transportasi Laut
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="transport_laut" 
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Transportasi Darat
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="transport_darat" 
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Transportasi Darat/Roro
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="transport_darat_roro" 
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Transportasi Udara
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="transport_udara" 
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Taksi
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="transport_taxi" 
                                    min="0"
                                    step="1000"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Lodging Section -->
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">II. Penginapan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="is_no_lodging" 
                                    id="is_no_lodging"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label for="is_no_lodging" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                    Tidak menginap (30% dari tarif maksimal)
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Malam Menginap
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="lodging_nights" 
                                    min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                    @if($is_no_lodging) disabled @endif
                                />
                            </div>
                        </div>
                        @if($lodging_rate > 0)
                        <div class="mt-3 p-3 bg-white dark:bg-gray-700 rounded border">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Tarif maksimal penginapan: <span class="font-semibold">Rp {{ number_format($lodging_rate, 0, ',', '.') }}/malam</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Perdiem Section -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">III. Uang Harian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kota Tujuan
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white">
                                    {{ $sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Hari
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white">
                                    {{ $sppd->days_count }} Hari
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tarif Harian
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white font-mono">
                                    Rp {{ number_format($perdiem_rate, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @if($perdiem_rate > 0)
                        <div class="mt-3 p-3 bg-white dark:bg-gray-700 rounded border">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Total uang harian: <span class="font-semibold">Rp {{ number_format($perdiem_rate * $sppd->days_count, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Representasi Section -->
                    @if($show_representasi)
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">IV. Representasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tarif Representasi
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white font-mono">
                                    Rp {{ number_format($representasi_rate, 0, ',', '.') }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Keterangan
                                </label>
                                <div class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 dark:border-gray-600 dark:text-white">
                                    Representasi untuk Eselon II
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes Section -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Catatan</h3>
                        <div>
                            <textarea 
                                wire:model="notes" 
                                rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                placeholder="Catatan tambahan (opsional)"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a 
                            href="{{ $this->getBackUrl() }}" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                        >
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
