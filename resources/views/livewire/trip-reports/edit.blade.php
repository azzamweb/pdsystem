<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="flex items-center mb-6">
                <a href="{{ $this->getBackUrl() }}" class="text-blue-600 hover:text-blue-800 mr-4">
                    ← Kembali ke Dokumen
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Edit Laporan Perjalanan Dinas</h2>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form wire:submit="update">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- SPT (Read-only) -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Surat Perintah Tugas (SPT)
                                </label>
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    <p class="text-sm text-gray-900">
                                        {{ $tripReport->spt->doc_no }}
                                    </p>
                                </div>
                            </div>

                            <!-- Informasi Perjalanan dari Nota Dinas (Read-only) -->
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Informasi Perjalanan (dari Nota Dinas)
                                </label>
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-300 rounded-md">
                                    <div class="grid grid-cols-2 gap-4 text-sm text-gray-900">
                                        <div>
                                            <span class="font-medium">Tempat Asal:</span> {{ $tripReport->spt->notaDinas->originPlace->name ?? 'N/A' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tempat Tujuan:</span> {{ $tripReport->spt->notaDinas->destinationCity->name ?? 'N/A' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tanggal Berangkat:</span> {{ $tripReport->spt->notaDinas->start_date ? \Carbon\Carbon::parse($tripReport->spt->notaDinas->start_date)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Tanggal Kembali:</span> {{ $tripReport->spt->notaDinas->end_date ? \Carbon\Carbon::parse($tripReport->spt->notaDinas->end_date)->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>



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
                                    Nomor Laporan
                                </label>
                                <input wire:model="report_no" type="text" id="report_no" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Nomor laporan (opsional)">
                            </div>

                            <!-- Kegiatan -->
                            <div class="sm:col-span-2">
                                <label for="activities" class="block text-sm font-medium text-gray-700">
                                    Kegiatan yang Dilakukan <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="activities" id="activities" rows="4" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Jelaskan kegiatan yang dilakukan selama perjalanan dinas..."></textarea>
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
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
