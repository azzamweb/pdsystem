<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Surat Perintah Perjalanan Dinas (SPPD)</h1>
            <p class="text-gray-600 dark:text-gray-400">Nomor: <span class="font-mono">{{ $sppd->doc_no ?? 'N/A' }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('sppd.pdf', $sppd) }}" target="_blank" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Lihat PDF</a>
            <a href="{{ route('sppd.edit', $sppd) }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Edit</a>
            <a href="{{ route('documents', ['nota_dinas_id' => $sppd->spt->nota_dinas_id, 'spt_id' => $sppd->spt_id, 'sppd_id' => $sppd->id]) }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">Kembali</a>
        </div>
    </div>

    @if (session('message'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <!-- Informasi Dasar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pegawai</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->user?->fullNameWithTitles() ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SPT</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->spt?->doc_no ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal SPPD</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->sppd_date ? \Carbon\Carbon::parse($sppd->sppd_date)->format('d/m/Y') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Informasi Perjalanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tempat Asal</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->originPlace?->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kota Tujuan</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->destinationCity?->name ?? 'N/A' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Moda Transportasi</label>
                <p class="text-gray-900 dark:text-white">
                    @if($sppd->transportModes && $sppd->transportModes->count() > 0)
                        {{ $sppd->transportModes->pluck('name')->implode(', ') }}
                    @else
                        N/A
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Perjalanan</label>
                <p class="text-gray-900 dark:text-white">
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
        </div>



        <!-- Informasi Tambahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sumber Dana</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->funding_source ?: 'Tidak diisi' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dibuat Pada</label>
                <p class="text-gray-900 dark:text-white">{{ $sppd->created_at ? \Carbon\Carbon::parse($sppd->created_at)->format('d/m/Y H:i') : 'N/A' }}</p>
            </div>
        </div>

        <!-- Rute Perjalanan -->
        @if($sppd->itineraries && $sppd->itineraries->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Rute Perjalanan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ke</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kendaraan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($sppd->itineraries as $i => $itinerary)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $i+1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $itinerary->travel_date ? \Carbon\Carbon::parse($itinerary->travel_date)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $itinerary->from_place ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $itinerary->to_place ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $itinerary->transport_mode ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
