<div>
    @if($sptId)
        @if(count($sppds) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nomor & Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pegawai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Penandatangan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Perjalanan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kwitansi
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($sppds as $sppd)
                            <tr 
                                wire:key="sppd-{{ $sppd->id }}"
                                class="cursor-pointer transition-colors {{ $selectedSppdId && $selectedSppdId == $sppd->id ? 'bg-green-200 dark:bg-green-800' : 'bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                                @if($selectedSppdId && $selectedSppdId == $sppd->id)
                                    style="background-color: #dcfce7 !important;"
                                @endif
                                wire:click="selectSppd({{ $sppd->id }})"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $sppd->doc_no }}
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ \Carbon\Carbon::parse($sppd->sppd_date)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $sppd->user->fullNameWithTitles() ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($sppd->signedByUser)
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $sppd->signedByUser->fullNameWithTitles() ?? 'N/A' }}
                                        </div>
                                        @if($sppd->assignment_title)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($sppd->assignment_title, 50) }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <div class="font-medium">{{ $sppd->spt?->notaDinas?->originPlace?->name ?? 'N/A' }} → {{ $sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if($sppd->transportModes && $sppd->transportModes->count() > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1">
                                                    {{ $sppd->transportModes->pluck('name')->implode(', ') }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
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
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    @if($sppd->receipts && $sppd->receipts->count() > 0)
                                        <!-- Kwitansi sudah ada - tampilkan tombol cetak dan edit -->
                                        <div class="flex flex-col space-y-1">
                                            <a 
                                                href="{{ route('receipts.show', $sppd->receipts->first()) }}" 
                                                target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800"
                                                title="Cetak Kwitansi"
                                            >
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                                Cetak
                                            </a>
                                            <a 
                                                href="{{ route('receipts.edit', $sppd->receipts->first()) }}"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                                title="Edit Kwitansi"
                                            >
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    @else
                                        <!-- Kwitansi belum ada - tampilkan tombol tambah -->
                                        <button 
                                            wire:click="createReceipt({{ $sppd->id }})"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800"
                                            title="Buat Kwitansi"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            + Kwitansi
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">


                                        <!-- Dropdown menu -->
                                        <div class="relative" x-data="{ open: false }">
                                            <!-- Dropdown trigger -->
                                            <button 
                                                @click="open = !open"
                                                @click.away="open = false"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded"
                                                title="Aksi"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div 
                                                x-show="open"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="fixed z-[99999] w-48 rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                style="display: none;"
                                                x-ref="dropdown"
                                                x-init="$watch('open', value => {
                                                    if (value) {
                                                        setTimeout(() => {
                                                            const button = $el.previousElementSibling;
                                                            const buttonRect = button.getBoundingClientRect();
                                                            
                                                            // Position to the left and above the button with smaller gap
                                                            const top = buttonRect.top - $el.offsetHeight - 4;
                                                            const left = buttonRect.left - $el.offsetWidth - 4;
                                                            
                                                            $el.style.top = Math.max(4, top) + 'px';
                                                            $el.style.left = Math.max(4, left) + 'px';
                                                        }, 10);
                                                    }
                                                })"
                                            >
                                                <div class="py-1">
                                                    <!-- Lihat PDF -->
                                                    <a 
                                                        href="{{ route('sppd.pdf', $sppd) }}" 
                                                        target="_blank"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    >
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                        </svg>
                                                        Cetak
                                                    </a>

                                                    <!-- Edit -->
                                                    <a 
                                                        href="{{ route('sppd.edit', $sppd) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    >
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Edit
                                                    </a>



                                                    <!-- Delete -->
                                                    @if(($sppd->itineraries && $sppd->itineraries->count() > 0) || ($sppd->receipts && $sppd->receipts->count() > 0))
                                                        <button 
                                                            disabled
                                                            class="flex items-center w-full px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed"
                                                            title="Tidak dapat dihapus karena memiliki data terkait"
                                                        >
                                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    @else
                                                        <button 
                                                            wire:click="confirmDelete({{ $sppd->id }})"
                                                            wire:confirm="Apakah Anda yakin ingin menghapus SPPD ini?"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                        >
                                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @php
            // Get participants without SPPD (always check, regardless of existing SPPDs)
            $participantsWithoutSppd = $this->getParticipantsWithoutSppd();
            $hasParticipantsWithoutSppd = $participantsWithoutSppd->count() > 0;
        @endphp

        @if($hasParticipantsWithoutSppd)
            <div class="mt-6 text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="mb-2">Ada {{ $participantsWithoutSppd->count() }} peserta yang belum memiliki SPPD</p>
                <p class="mb-4 text-sm text-gray-400">Klik tombol di bawah untuk membuat SPPD</p>
                
                <button 
                    wire:click="createSppd({{ $sptId }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Buat SPPD
                </button>
            </div>
        @elseif(count($sppds) == 0)
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mb-4">Belum ada SPPD untuk SPT ini</p>
            </div>
        @endif
    @else

        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p>Pilih SPT untuk melihat SPPD</p>

        </div>
    @endif
</div>
