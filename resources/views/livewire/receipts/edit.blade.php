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
                    <!-- Informasi Nota Dinas dan SPT sebagai Referensi -->
                    @if($receipt->sppd->spt && $receipt->sppd->spt->notaDinas)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Nota Dinas & SPT (Referensi)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <!-- Nota Dinas Info -->
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Nomor Nota Dinas:</span>
                                <p class="text-gray-900 dark:text-white font-mono">{{ $receipt->sppd->spt->notaDinas->doc_no }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tanggal Nota Dinas:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->nd_date ? \Carbon\Carbon::parse($receipt->sppd->spt->notaDinas->nd_date)->locale('id')->translatedFormat('d F Y') : '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Bidang Pengaju:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->requestingUnit->name ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Dari:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->fromUser->fullNameWithTitles() ?? '-' }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $receipt->sppd->spt->notaDinas->fromUser->position->name ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Kepada:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->toUser->fullNameWithTitles() ?? '-' }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $receipt->sppd->spt->notaDinas->toUser->position->name ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tujuan:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->destinationCity->name ?? '-' }}, {{ $receipt->sppd->spt->notaDinas->destinationCity->province->name ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Periode Perjalanan:</span>
                                <p class="text-gray-900 dark:text-white">
                                    {{ $receipt->sppd->spt->notaDinas->start_date ? \Carbon\Carbon::parse($receipt->sppd->spt->notaDinas->start_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                                    s.d
                                    {{ $receipt->sppd->spt->notaDinas->end_date ? \Carbon\Carbon::parse($receipt->sppd->spt->notaDinas->end_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    ({{ $receipt->sppd->spt->notaDinas->start_date && $receipt->sppd->spt->notaDinas->end_date ? \Carbon\Carbon::parse($receipt->sppd->spt->notaDinas->start_date)->diffInDays(\Carbon\Carbon::parse($receipt->sppd->spt->notaDinas->end_date)) + 1 : 0 }} hari)
                                </p>
                            </div>
                            <div class="space-y-1 md:col-span-2 lg:col-span-3">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Hal:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->notaDinas->hal }}</p>
                            </div>
                            
                            <!-- SPT Info -->
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Nomor SPT:</span>
                                <p class="text-gray-900 dark:text-white font-mono">{{ $receipt->sppd->spt->doc_no }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Tanggal SPT:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->spt_date ? \Carbon\Carbon::parse($receipt->sppd->spt->spt_date)->locale('id')->translatedFormat('d F Y') : '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">Penandatangan SPT:</span>
                                <p class="text-gray-900 dark:text-white">{{ $receipt->sppd->spt->signedByUser->fullNameWithTitles() ?? '-' }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $receipt->sppd->spt->signedByUser->position->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                    Tanggal SPPD
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $receipt->sppd->sppd_date ? \Carbon\Carbon::parse($receipt->sppd->sppd_date)->locale('id')->translatedFormat('d F Y') : '-' }}
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Penerima Pembayaran
                                </label>
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $receipt->payeeUser->fullNameWithTitles() ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $receipt->payeeUser->position?->name ?? 'N/A' }} - {{ $receipt->payeeUser->unit?->name ?? 'N/A' }}
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

                            <!-- Tingkat Perjalanan Dinas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tingkat Perjalanan Dinas <span class="text-red-500">*</span>
                                </label>
                                @php
                                    $travelGrades = \App\Models\TravelGrade::orderBy('name')->get();
                                @endphp
                                <select 
                                    wire:model="travel_grade_id" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >
                                    <option value="">Pilih Tingkat Perjalanan Dinas</option>
                                    @foreach($travelGrades as $travelGrade)
                                        <option value="{{ $travelGrade->id }}">
                                            {{ $travelGrade->name }} ({{ $travelGrade->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @php
                                        $selectedParticipant = $receipt->sppd->spt->notaDinas->participants->where('user_id', $receipt->payee_user_id)->first();
                                        $hasTravelGrade = $selectedParticipant?->user_travel_grade_id_snapshot || $selectedParticipant?->user?->travel_grade_id;
                                    @endphp
                                    @if($hasTravelGrade)
                                        <span class="text-green-600 dark:text-green-400">
                                            ✓ Tingkat perjalanan dinas peserta sudah ditentukan
                                        </span>
                                    @else
                                        <span class="text-yellow-600 dark:text-yellow-400">
                                            ⚠ Tingkat perjalanan dinas peserta belum ditentukan, silakan pilih tingkat perjalanan dinas
                                        </span>
                                    @endif
                                </div>
                                @error('travel_grade_id') 
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
