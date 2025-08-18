<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Nota Dinas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lihat detail lengkap Nota Dinas</p>
        </div>
        <div>
            <a href="{{ route('nota-dinas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Nomor Nota Dinas</div>
                <div class="font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $notaDinas->doc_no }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Tanggal Nota Dinas</div>
                <div class="text-gray-900 dark:text-white">{{ $notaDinas->nd_date ? \Carbon\Carbon::parse($notaDinas->nd_date)->format('d/m/Y') : '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Bidang Pengaju</div>
                <div class="text-gray-900 dark:text-white">{{ $notaDinas->requestingUnit?->name ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Kota/Kab Tujuan</div>
                <div class="text-gray-900 dark:text-white">{{ $notaDinas->destinationCity?->name ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Perihal</div>
                <div class="text-gray-900 dark:text-white">{{ $notaDinas->hal }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Status</div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notaDinas->status === 'DRAFT' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : ($notaDinas->status === 'SUBMITTED' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ($notaDinas->status === 'APPROVED' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
                    {{ $notaDinas->status }}
                </span>
            </div>
            <div class="md:col-span-2 border-t pt-4 mt-4">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Peserta (Pegawai yang bepergian):</div>
                <ul class="list-disc pl-6">
                    @foreach($notaDinas->participants as $p)
                        <li class="text-gray-900 dark:text-white">{{ $p->user->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="md:col-span-2 border-t pt-4 mt-4">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Audit Nomor</div>
                <div class="text-xs text-gray-700 dark:text-gray-300">@if($notaDinas->number_is_manual) <span class="text-yellow-600">Override manual</span> @endif</div>
                <div class="text-xs text-gray-700 dark:text-gray-300">Format: {{ $notaDinas->numberFormat?->format_string ?? '-' }}</div>
                <div class="text-xs text-gray-700 dark:text-gray-300">Sequence: {{ $notaDinas->number_sequence_id }}</div>
                <div class="text-xs text-gray-700 dark:text-gray-300">Scope Unit: {{ $notaDinas->number_scope_unit_id }}</div>
            </div>
        </div>
    </div>
</div>
