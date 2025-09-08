<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Rekap Global Perjalanan Dinas</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Rekapitulasi menyeluruh semua dokumen perjalanan dinas (Nota Dinas, SPT, SPPD, Kwitansi, Laporan Perjalanan Dinas, Dokumen Pendukung).
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="exportPdf" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </button>
            <button wire:click="exportExcel" class="ml-3 inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex-grow max-w-xs">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input id="search" wire:model.live.debounce.300ms="search" type="search" name="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600" placeholder="Cari dokumen, pegawai, tujuan..." />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="dateFrom" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" wire:model.live="dateFrom" id="dateFrom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>
                <div>
                    <label for="dateTo" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                    <input type="date" wire:model.live="dateTo" id="dateTo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>
                <div>
                    <label for="locationFilter" class="block text-xs font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                    <select wire:model.live="locationFilter" id="locationFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">Semua Lokasi</option>
                        @foreach($locationOptions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="clearFilters" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Clear Filters
                </button>
            </div>
        </div>

        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">No. Nota Dinas</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Asal & Tujuan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Maksud</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">No. & Tanggal SPT</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Penandatangan SPT</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">No. & Tanggal SPPD</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Penandatangan SPPD</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Alat Angkutan</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama PPTK</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">No. & Tanggal Laporan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @forelse($rekapData as $item)
                                <tr>
                                    <!-- No. & Tanggal -->
                                    <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('nota-dinas.show', $item['id']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                                {{ $item['number'] ?: 'N/A' }}
                                            </a>
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            {{ $item['date'] ? \Carbon\Carbon::parse($item['date'])->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        @if($item['requesting_unit'])
                                            <div class="text-xs text-gray-400 mt-1">
                                                Bidang: {{ $item['requesting_unit'] }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Asal & Tujuan -->
                                    <td class="px-3 py-4 text-sm">
                                        <div class="text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ $item['origin'] }}</div>
                                            <div class="text-gray-500 dark:text-gray-400">→ {{ $item['destination'] }}</div>
                                        </div>
                                        @if($item['start_date'] && $item['end_date'])
                                            <div class="mt-1 text-xs text-gray-400">
                                                {{ \Carbon\Carbon::parse($item['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item['end_date'])->format('d/m/Y') }}
                                                <span class="ml-1">({{ $item['duration'] ?: \Carbon\Carbon::parse($item['start_date'])->diffInDays(\Carbon\Carbon::parse($item['end_date'])) + 1 }} Hari)</span>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Maksud -->
                                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item['maksud'] ?: 'N/A' }}
                                    </td>

                                    <!-- No. & Tanggal SPT -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['spt_number'])
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('spt.pdf', $item['spt_id']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                                    {{ $item['spt_number'] }}
                                                </a>
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">
                                                {{ $item['spt_date'] ? \Carbon\Carbon::parse($item['spt_date'])->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- Penandatangan SPT -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['spt_signer'])
                                            <div class="text-gray-900 dark:text-white">
                                                {{ $item['spt_signer'] }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- No. & Tanggal SPPD -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['sppd_number'])
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('sppd.pdf', $item['sppd_id']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                                    {{ $item['sppd_number'] }}
                                                </a>
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">
                                                {{ $item['sppd_date'] ? \Carbon\Carbon::parse($item['sppd_date'])->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- Penandatangan SPPD -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['sppd_signer'])
                                            <div class="text-gray-900 dark:text-white">
                                                {{ $item['sppd_signer'] }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- Alat Angkutan -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['transport_mode'])
                                            <div class="text-gray-900 dark:text-white">
                                                {{ $item['transport_mode'] }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- Nama PPTK -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['pptk_name'])
                                            <div class="text-gray-900 dark:text-white">
                                                {{ $item['pptk_name'] }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <!-- No. & Tanggal Laporan -->
                                    <td class="px-3 py-4 text-sm">
                                        @if($item['trip_report_number'])
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('trip-reports.pdf', $item['trip_report_id']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                                    {{ $item['trip_report_number'] }}
                                                </a>
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">
                                                {{ $item['trip_report_date'] ? \Carbon\Carbon::parse($item['trip_report_date'])->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 text-center dark:text-white">
                                        @if($loading)
                                            <div class="flex items-center justify-center">
                                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                                                <span class="ml-2">Memuat data...</span>
                                            </div>
                                        @else
                                            Tidak ada data nota dinas yang ditemukan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($totalRecords > $perPage)
            <div class="mt-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ (($this->getPage() - 1) * $perPage) + 1 }} sampai {{ min($this->getPage() * $perPage, $totalRecords) }} dari {{ $totalRecords }} data
                    </div>
                    <div class="flex space-x-2">
                        @if($this->getPage() > 1)
                            <button wire:click="previousPage" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">Previous</button>
                        @endif
                        @if($this->getPage() < $this->getTotalPages())
                            <button wire:click="nextPage" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>