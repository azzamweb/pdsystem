<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Buat Kwitansi
                    </h2>
                    <a 
                        href="{{ route('documents') }}" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                    >
                        Kembali
                    </a>
                </div>

                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit="save">
                    <!-- SPPD Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi SPPD</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor SPPD
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $sppd->doc_no }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tujuan
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $sppd->destinationCity->name }}, {{ $sppd->destinationCity->province->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Durasi
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $sppd->days_count }} hari
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jenis Perjalanan
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $sppd->trip_type }}
                                </div>
                            </div>
                        </div>
                    </div>

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
                                    @if($sppd->user->travelGradeMap && $sppd->user->travelGradeMap->travelGrade)
                                        {{ $sppd->user->travelGradeMap->travelGrade->name }}
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
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    wire:model="is_no_lodging" 
                                    id="is_no_lodging"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label for="is_no_lodging" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                    Tidak menginap (30% dari tarif maksimal: Rp {{ number_format($lodging_rate * 0.3, 0, ',', '.') }})
                                </label>
                            </div>
                            
                            @if(!$is_no_lodging)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Malam Menginap
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="lodging_nights" 
                                    min="0"
                                    max="{{ $sppd->days_count }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="0"
                                />
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Tarif per malam: Rp {{ number_format($lodging_rate, 0, ',', '.') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Perdiem Section -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">III. Uang Harian</h3>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-900 dark:text-white">
                                <strong>{{ $sppd->destinationCity->name }}</strong> 
                                ({{ $sppd->days_count }} hari × Rp {{ number_format($perdiem_rate, 0, ',', '.') }})
                            </div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                Total: Rp {{ number_format($perdiem_rate * $sppd->days_count, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Representasi Section -->
                    @if($show_representasi)
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">IV. Representatif</h3>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-900 dark:text-white">
                                <strong>Eselon II atau setara</strong>
                            </div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white mt-2">
                                Total: Rp {{ number_format($representasi_rate, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan
                        </label>
                        <textarea 
                            wire:model="notes" 
                            rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Catatan tambahan..."
                        ></textarea>
                        @error('notes') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a 
                            href="{{ route('documents') }}" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                        >
                            Batal
                        </a>
                        <button 
                            type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                        >
                            <span wire:loading.remove>Buat Kwitansi</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
