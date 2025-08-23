<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Surat Perintah Tugas (SPT)</h1>
        <a href="{{ route('spt.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Buat SPT</a>
    </div>

    @if (session('message'))
        <div class="p-3 rounded-md bg-green-100 text-green-800 border border-green-200">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="p-3 rounded-md bg-red-100 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-3 py-2">No. SPT</th>
                        <th class="text-left px-3 py-2">Tanggal</th>
                        <th class="text-left px-3 py-2">Nota Dinas</th>
                        <th class="text-left px-3 py-2">Penandatangan</th>
                        <th class="text-left px-3 py-2">Keterangan</th>
                        <th class="text-right px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($spts as $spt)
                        <tr>
                            <td class="px-3 py-2 font-mono">{{ $spt->doc_no }}</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($spt->spt_date)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">
                                <a href="{{ route('nota-dinas.show', $spt->nota_dinas_id) }}" class="text-blue-600 hover:underline">{{ $spt->notaDinas?->doc_no }}</a>
                            </td>
                            <td class="px-3 py-2">{{ $spt->signedByUser?->fullNameWithTitles() }}</td>
                            <td class="px-3 py-2 text-gray-500">-</td>
                            <td class="px-3 py-2 text-right">
                                <a href="{{ route('spt.pdf', $spt) }}" target="_blank" class="text-green-600 hover:underline mr-3">PDF</a>
                                <a href="{{ route('spt.pdf', $spt) }}" target="_blank" class="text-green-600 hover:underline mr-3">PDF</a>
                                <a href="{{ route('spt.pdf-download', $spt) }}" class="text-orange-600 hover:underline mr-3">Download</a>
                                <a href="{{ route('spt.edit', $spt) }}" class="text-gray-600 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-3 py-4 text-center text-gray-500">Belum ada data SPT</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
