<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Edit Kwitansi
                    </h2>
                    <a 
                        href="{{ $this->getBackUrl() }}" 
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

                <form wire:submit="update">
                    <!-- SPPD Information -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi SPPD</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor SPPD
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $receipt->sppd->doc_no }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tujuan
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $receipt->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A' }}, {{ $receipt->sppd->spt?->notaDinas?->destinationCity?->province?->name ?? 'N/A' }}
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="space-y-6">
                            <!-- Kode Rekening Kegiatan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kode Rekening Kegiatan
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="account_code" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Contoh: 2.2.1.01.01.0001"
                                />
                                @error('account_code') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Nama Bendahara -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nama Bendahara <span class="text-red-500">*</span>
                                </label>
                                <div x-data="searchableSelect({
                                    options: {{ Js::from(\App\Models\User::orderBy('name')->get()->map(function($user) {
                                        return [
                                            'id' => $user->id,
                                            'text' => $user->fullNameWithTitles() . ' (' . trim(($user->position?->name ?? '') . ' ' . ($user->unit?->name ?? '')) . ')',
                                            'name' => $user->name,
                                            'nip' => $user->nip,
                                            'position' => $user->position?->name,
                                            'unit' => $user->unit?->name
                                        ];
                                    })) }},
                                    selectedValue: @entangle('treasurer_user_id'),
                                    placeholder: 'Cari dan pilih bendahara...'
                                })">
                                    <!-- Search Input -->
                                    <div class="relative mt-1">
                                        <input 
                                            type="text" 
                                            x-ref="searchInput"
                                            x-model="searchTerm"
                                            @click="open = true"
                                            @keydown.escape="open = false"
                                            @keydown.arrow-down.prevent="selectNext()"
                                            @keydown.arrow-up.prevent="selectPrevious()"
                                            @keydown.enter.prevent="selectCurrent()"
                                            placeholder="Cari dan pilih bendahara..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            :class="{ 'border-blue-500': open }"
                                        >
                                        
                                        <!-- Dropdown -->
                                        <div 
                                            x-show="open" 
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 transform scale-100"
                                            x-transition:leave-end="opacity-0 transform scale-95"
                                            @click.away="open = false"
                                            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                        >
                                            <template x-for="(option, index) in filteredOptions" :key="option.id">
                                                <div 
                                                    @click="selectOption(option)"
                                                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    :class="{ 'bg-blue-100 dark:bg-blue-900': index === selectedIndex }"
                                                >
                                                    <div class="font-medium" x-text="option.text"></div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400" x-text="'NIP: ' + (option.nip || '-')"></div>
                                                </div>
                                            </template>
                                            
                                            <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-gray-500 dark:text-gray-400">
                                                Tidak ada hasil yang ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('treasurer_user_id') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Titel Bendahara -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Titel Bendahara <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    wire:model="treasurer_title" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="">Pilih Titel</option>
                                    <option value="Bendahara Pengeluaran">Bendahara Pengeluaran</option>
                                    <option value="Bendahara Pengeluaran Pembantu">Bendahara Pengeluaran Pembantu</option>
                                </select>
                                @error('treasurer_title') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Tanggal Kwitansi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Kwitansi <span class="text-red-500">*</span>
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

                            <!-- Nomor Kwitansi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Kwitansi (SIPD)
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="receipt_no" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Nomor dari aplikasi SIPD (opsional)"
                                />
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Nomor kwitansi akan diisi dari aplikasi SIPD. Bisa dikosongkan untuk sementara.
                                </div>
                                @error('receipt_no') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end space-x-3">
                            <a href="{{ $this->getBackUrl() }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
