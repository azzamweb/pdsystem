<div class="p-6">
    <div class="min-h-screen dark:bg-gray-900">
        <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Perjadin / Dokumen
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Kelola dokumen perjalanan dinas secara terintegrasi
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('nota-dinas.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="font-medium">Buat Nota Dinas</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <!-- Nota Dinas List (Master) -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 ">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-200 dark:bg-blue-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Nota Dinas
                        </h2>
                    </div>
                    <div class="p-0">
                        @livewire('documents.nota-dinas-list', ['selectedNotaDinasId' => $selectedNotaDinasId])
                    </div>
                </div>
            </div>

            <!-- SPT Table (Child) - Only show if Nota Dinas is selected -->
            @if($selectedNotaDinasId)
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-purple-200 dark:bg-purple-800">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Surat Perintah Tugas (SPT)
                            </h2>
                        </div>
                        <div class="p-0">
                            @livewire('documents.spt-table', ['notaDinasId' => $selectedNotaDinasId])
                        </div>
                    </div>
                </div>

                <!-- SPPD Table (Child) - Only show if SPT is selected -->
                @if($selectedSptId)
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-green-200 dark:bg-green-800">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Surat Perintah Perjalanan Dinas (SPPD)
                                </h2>
                            </div>
                            <div class="p-0">
                                @livewire('documents.sppd-table', ['sptId' => $selectedSptId])
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Placeholder when no SPT selected -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Surat Perintah Perjalanan Dinas (SPPD)
                                </h2>
                            </div>
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p>Pilih SPT untuk melihat SPPD</p>
                            </div>
                        </div>
                    </div>
                @endif



                <!-- Laporan Perjalanan Dinas Section - Only show if SPT is selected -->
                @if($selectedSptId && $selectedSpt)
                    <!-- Kolom 1: Laporan Perjalanan Dinas -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-200 dark:bg-blue-800">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Laporan Perjalanan Dinas
                                </h2>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    <!-- Trip Report Status -->
                                    @php
                                        $tripReport = $selectedSptId ? \App\Models\TripReport::where('spt_id', $selectedSptId)->first() : null;
                                    @endphp
                                    
                                    @if($tripReport)
                                        <!-- Informasi Laporan - Compact & Informative -->
                                        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                            <!-- Header Status -->
                                            <div class="flex items-center gap-2 mb-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">Laporan Tersedia</span>
                                            </div>
                                            
                                            <!-- Informasi Detail -->
                                            <div class="space-y-2 text-xs">
                                                <!-- Nomor Laporan -->
                                                @if($tripReport->doc_no)
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-700 dark:text-gray-300">No:</span>
                                                        <span class="text-green-700 dark:text-green-300 font-mono">{{ $tripReport->doc_no }}</span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Tanggal Laporan -->
                                                @if($tripReport->report_date)
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-medium text-gray-700 dark:text-gray-300">Tanggal:</span>
                                                        <span class="text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($tripReport->report_date)->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Ringkasan Kegiatan -->
                                                @if($tripReport->activities)
                                                    <div class="mt-3 pt-2 border-t border-green-200 dark:border-green-700">
                                                        <div class="font-medium text-gray-700 dark:text-gray-300 mb-1">Ringkasan Kegiatan:</div>
                                                        <div class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                                            {{ Str::limit($tripReport->activities, 150, '...') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Action Buttons - Compact Icon Row -->
                                        <div class="flex items-center justify-center gap-3 mt-4">
                                            <!-- Cetak Laporan -->
                                            <button 
                                                onclick="window.open('{{ route('trip-reports.pdf', $tripReport) }}', '_blank')"
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors group"
                                                title="Cetak Laporan"
                                            >
                                                <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-800 dark:text-gray-300 dark:group-hover:text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Edit Laporan -->
                                            <button 
                                                onclick="window.location.href='{{ route('trip-reports.edit', $tripReport) }}'"
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 transition-colors group"
                                                title="Edit Laporan"
                                            >
                                                <svg class="w-6 h-6 text-blue-600 group-hover:text-blue-800 dark:text-blue-300 dark:group-hover:text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            
                                            <!-- Hapus Laporan -->
                                            <button 
                                                wire:click="deleteTripReport({{ $tripReport->id }})"
                                                wire:confirm="Apakah Anda yakin ingin menghapus laporan perjalanan dinas ini?"
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 transition-colors group"
                                                title="Hapus Laporan"
                                            >
                                                <svg class="w-6 h-6 text-red-600 group-hover:text-red-800 dark:text-red-300 dark:group-hover:text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Laporan Belum Dibuat</span>
                                            </div>
                                            <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Buat laporan perjalanan dinas untuk SPT ini</p>
                                        </div>
                                        
                                        <button 
                                            wire:click="createLaporanPd"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Buat Laporan Perjalanan Dinas
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                @else
                    <!-- Placeholder when no SPT selected for Laporan -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Pilih SPT untuk mengelola laporan</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Dokumen Pendukung Section - Only show if Nota Dinas is selected -->
                @if($selectedNotaDinasId && $selectedNotaDinas)
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-green-200 dark:bg-green-800">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Dokumen Pendukung
                                </h2>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    @php
                                        $supportingDocuments = \App\Models\SupportingDocument::where('nota_dinas_id', $selectedNotaDinasId)->get();
                                    @endphp
                                    
                                    @if($supportingDocuments->count() > 0)
                                        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ $supportingDocuments->count() }} Dokumen Tersedia</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Daftar Dokumen -->
                                        <div class="space-y-2 max-h-40 overflow-y-auto">
                                            @foreach($supportingDocuments as $doc)
                                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-900 dark:text-white truncate">{{ $doc->title }}</div>
                                                        <div class="text-gray-500 dark:text-gray-400">{{ $doc->document_type }}</div>
                                                    </div>
                                                    <div class="flex items-center space-x-2 ml-2">
                                                        <a href="{{ $doc->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50" title="Download">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('supporting-documents.edit', ['notaDinas' => $selectedNotaDinasId, 'document' => $doc->id]) }}" 
                                                           class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50" title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <button wire:click="deleteSupportingDocument({{ $doc->id }})"
                                                                wire:confirm="Apakah Anda yakin ingin menghapus dokumen '{{ $doc->title }}'?"
                                                                class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50" title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Belum Ada Dokumen</span>
                                            </div>
                                            <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Upload dokumen pendukung untuk Nota Dinas ini</p>
                                        </div>
                                    @endif
                                    
                                    <!-- Upload Dokumen Button -->
                                    <button 
                                        onclick="window.location.href='{{ route('supporting-documents.upload', $selectedNotaDinasId) }}'"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Upload Dokumen Pendukung
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Placeholder when no Nota Dinas selected for Dokumen Pendukung -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p>Pilih Nota Dinas untuk mengelola dokumen pendukung</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Placeholder when no Nota Dinas selected -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium mb-2">Pilih Nota Dinas</p>
                            <p class="text-sm">Pilih Nota Dinas dari daftar di sebelah kiri untuk melihat SPT dan SPPD terkait</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
