<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center mb-6">
                <a href="{{ $this->getBackUrl() }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Kembali ke Dokumen
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Buat Laporan Perjalanan Dinas</h2>
            </div>

            @if (session()->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Info SPT & Nota Dinas -->
                            @if($spt)
                                <div class="p-4 bg-gray-50 border rounded-md">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">Nomor SPT:</span>
                                            <div class="text-gray-900">{{ $spt->doc_no }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Nota Dinas:</span>
                                            <div class="text-gray-900">{{ $spt->notaDinas?->doc_no ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Tempat Asal:</span>
                                            <div class="text-gray-900">{{ $spt->notaDinas?->originPlace?->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Tujuan:</span>
                                            <div class="text-gray-900">{{ $spt->notaDinas?->destinationCity?->name ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Tanggal:</span>
                                            <div class="text-gray-900">
                                                {{ optional($spt->notaDinas?->start_date ?? $spt->start_date)->format('Y-m-d') ?? '-' }}
                                                -
                                                {{ optional($spt->notaDinas?->end_date ?? $spt->end_date)->format('Y-m-d') ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Tanggal Laporan -->
                            <div>
                                <label for="report_date" class="block text-sm font-medium text-gray-700">
                                    Tanggal Laporan <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="report_date" type="date" id="report_date" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('report_date') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Laporan -->
                            <div>
                                <label for="report_no" class="block text-sm font-medium text-gray-700">
                                    Nomor Laporan (opsional)
                                </label>
                                <input wire:model="report_no" type="text" id="report_no" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Biarkan kosong jika belum ada">
                            </div>

                            <!-- Kegiatan -->
                            <div>
                                <x-enhanced-textarea 
                                    id="activities"
                                    model="activities"
                                    label="Kegiatan yang Dilakukan <span class='text-red-500'>*</span>"
                                    placeholder="Jelaskan kegiatan yang dilakukan selama perjalanan dinas... Gunakan bullet points atau numbering untuk struktur yang lebih baik."
                                    rows="8"
                                />
                                @error('activities') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
