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
                                Perjalanan
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <div class="font-medium">{{ $sppd->originPlace->name ?? 'N/A' }} → {{ $sppd->destinationCity->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($sppd->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sppd->end_date)->format('d/m/Y') }} ({{ $sppd->days_count }} hari)
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                {{ $sppd->trip_type === 'LUAR_DAERAH' ? 'Luar Daerah' : 'Dalam Daerah' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <!-- Status badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sppd->status === 'COMPLETED' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                            {{ $sppd->status === 'COMPLETED' ? 'Selesai' : ($sppd->status ?? 'Aktif') }}
                                        </span>

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
                                                class="fixed z-[9999] mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                style="display: none;"
                                                x-ref="dropdown"
                                                x-init="$watch('open', value => {
                                                    if (value) {
                                                        const rect = $el.getBoundingClientRect();
                                                        const button = $el.previousElementSibling;
                                                        const buttonRect = button.getBoundingClientRect();
                                                        
                                                        $el.style.top = buttonRect.bottom + 'px';
                                                        $el.style.left = (buttonRect.right - $el.offsetWidth) + 'px';
                                                    }
                                                })"
                                            >
                                                <div class="py-1">
                                                    <!-- Cetak -->
                                                    <a 
                                                        href="{{ route('sppd.show', $sppd) }}" 
                                                        target="_blank"
                                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                    >
                                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                        </svg>
                                                        Cetak
                                                    </a>
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
        @else

            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{-- <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path> --}}
                </svg>
                <p class="mb-4">Belum ada SPPD untuk SPT ini</p>

                <button 
                    wire:click="createSppd({{ $sptId }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Buat SPPD
                </button>
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
