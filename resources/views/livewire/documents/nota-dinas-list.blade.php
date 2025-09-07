<div>
    <!-- Search and Filters -->
    <div class="mb-4" style="padding: 10px;">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search -->
            <div class="flex-1 min-w-0">
                <input 
                    wire:model.debounce.400ms="search" 
                    type="text" 
                    placeholder="Cari berdasarkan nomor, unit, tujuan, atau nama peserta..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Date From -->
            <div class="w-40">
                <input 
                    wire:model="dateFrom" 
                    type="date" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Date To -->
            <div class="w-40">
                <input 
                    wire:model="dateTo" 
                    type="date" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <!-- Unit Filter -->
            <div class="w-48">
                <select 
                    wire:model="unitFilter" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-40">
                <select 
                    wire:model="statusFilter" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="APPROVED">Approved</option>
                </select>
            </div>

            <!-- Reset Button -->
            <div>
                <button 
                    wire:click="resetFilters"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Nota Dinas Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 ">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nomor & Tanggal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Unit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Judul Penandatangan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Tujuan
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Peserta
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Lama
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notaDinasList as $notaDinas)
                    <tr 
                        wire:key="nd-{{ $notaDinas->id }}"
                        class="cursor-pointer transition-colors {{ $selectedNotaDinasId && $selectedNotaDinasId == $notaDinas->id ? 'bg-blue-200 dark:bg-blue-800' : ' dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                        @if($selectedNotaDinasId && $selectedNotaDinasId == $notaDinas->id)
                            style="background-color: #dbeafe !important;"
                        @endif
                        wire:click="selectNotaDinas({{ $notaDinas->id }})"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notaDinas->doc_no }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($notaDinas->nd_date)->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $notaDinas->requestingUnit->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            @if($notaDinas->custom_signer_title)
                                <div class="whitespace-pre-line">{{ $notaDinas->custom_signer_title }}</div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $notaDinas->destinationCity->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $notaDinas->status === 'APPROVED' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                {{ $notaDinas->status === 'APPROVED' ? 'Disetujui' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-900 dark:text-white">
                            @if($notaDinas->participants->count() > 0)
                                @php
                                    $sortedParticipants = $notaDinas->getSortedParticipants();
                                @endphp
                                <div class="space-y-1">
                                    @foreach($sortedParticipants as $participant)
                                        <div class="truncate">
                                            {{ $participant->user_name_snapshot ?: $participant->user->name }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-900 dark:text-white">
                            @if($notaDinas->start_date && $notaDinas->end_date)
                                <div class="space-y-1">
                                    <div class="truncate">
                                        <span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($notaDinas->start_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="truncate">
                                        <span class="font-medium">Kembali:</span> {{ \Carbon\Carbon::parse($notaDinas->end_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-gray-500 dark:text-gray-400">
                                        ({{ \Carbon\Carbon::parse($notaDinas->start_date)->diffInDays(\Carbon\Carbon::parse($notaDinas->end_date)) + 1 }} hari)
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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
                                        <!-- Cetak -->
                                        <a 
                                            href="{{ route('nota-dinas.pdf', $notaDinas) }}" 
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
                                            href="{{ route('nota-dinas.edit', $notaDinas) }}"
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>

                                        <!-- Delete -->
                                        @php
                                            $hasActiveSpt = $notaDinas->spt && $notaDinas->spt->exists;
                                            $hasActiveSupportingDocs = $notaDinas->supportingDocuments && $notaDinas->supportingDocuments->where('is_active', true)->count() > 0;
                                        @endphp
                                        @if($hasActiveSpt || $hasActiveSupportingDocs)
                                            <button 
                                                disabled
                                                class="flex w-full items-center px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed"
                                                title="Tidak dapat dihapus karena memiliki dokumen turunan (SPT atau dokumen pendukung)"
                                            >
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        @else
                                            <button 
                                                wire:click="confirmDelete({{ $notaDinas->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus Nota Dinas ini?"
                                                class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Tidak ada Nota Dinas ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($notaDinasList->hasPages())
        <div class="mt-4">
            {{ $notaDinasList->links() }}
        </div>
    @endif
</div>
