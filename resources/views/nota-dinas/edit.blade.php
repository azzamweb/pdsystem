<x-layouts.app.sidebar title="Edit Nota Dinas">
    <flux:main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Header -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Nota Dinas</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Form pengeditan Nota Dinas - {{ $notaDinas->doc_no ?? 'Draft' }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('documents') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Error Messages -->
                @if (session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors && $errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terjadi kesalahan:</h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Overlap Details -->
                @if (session('overlap_details'))
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-3">
                                    ⚠️ Terdapat pegawai yang tanggalnya beririsan dengan Nota Dinas lain:
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-yellow-300 dark:border-yellow-600">
                                                <th class="px-3 py-2 text-left font-medium text-yellow-800 dark:text-yellow-200">Pegawai</th>
                                                <th class="px-3 py-2 text-left font-medium text-yellow-800 dark:text-yellow-200">Nomor ND</th>
                                                <th class="px-3 py-2 text-left font-medium text-yellow-800 dark:text-yellow-200">Bidang</th>
                                                <th class="px-3 py-2 text-left font-medium text-yellow-800 dark:text-yellow-200">Perihal</th>
                                                <th class="px-3 py-2 text-left font-medium text-yellow-800 dark:text-yellow-200">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (session('overlap_details') as $overlap)
                                                <tr class="border-b border-yellow-200 dark:border-yellow-700">
                                                    <td class="px-3 py-2 text-yellow-700 dark:text-yellow-300">{{ $overlap['user'] }}</td>
                                                    <td class="px-3 py-2 text-yellow-700 dark:text-yellow-300">{{ $overlap['doc_no'] }}</td>
                                                    <td class="px-3 py-2 text-yellow-700 dark:text-yellow-300">{{ $overlap['unit'] }}</td>
                                                    <td class="px-3 py-2 text-yellow-700 dark:text-yellow-300">{{ $overlap['hal'] }}</td>
                                                    <td class="px-3 py-2 text-yellow-700 dark:text-yellow-300">
                                                        {{ \Carbon\Carbon::parse($overlap['start_date'])->format('d/m/Y') }} - 
                                                        {{ \Carbon\Carbon::parse($overlap['end_date'])->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('nota-dinas.update', $notaDinas) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Dasar</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Unit Pemohon -->
                                <div>
                                    <label for="requesting_unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Unit Pemohon <span class="text-red-500">*</span>
                                    </label>
                                    <select name="requesting_unit_id" id="requesting_unit_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('requesting_unit_id', $notaDinas->requesting_unit_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors && $errors->has('requesting_unit_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('requesting_unit_id') }}</p>
                                    @endif
                                </div>

                                <!-- Kepada -->
                                <div>
                                    <label for="to_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kepada <span class="text-red-500">*</span>
                                    </label>
                                    <select name="to_user_id" id="to_user_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih Pegawai</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('to_user_id', $notaDinas->to_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->fullNameWithTitles() }} - {{ $user->position?->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors && $errors->has('to_user_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('to_user_id') }}</p>
                                    @endif
                                </div>

                                <!-- Dari -->
                                <div>
                                    <label for="from_user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dari <span class="text-red-500">*</span>
                                    </label>
                                    <select name="from_user_id" id="from_user_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih Pegawai</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('from_user_id', $notaDinas->from_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->fullNameWithTitles() }} - {{ $user->position?->name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors && $errors->has('from_user_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('from_user_id') }}</p>
                                    @endif
                                </div>

                                <!-- Kota Tujuan -->
                                <div>
                                    <label for="destination_city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kota Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="destination_city_id" id="destination_city_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih Kota</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('destination_city_id', $notaDinas->destination_city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors && $errors->has('destination_city_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('destination_city_id') }}</p>
                                    @endif
                                </div>

                                <!-- Tempat Asal -->
                                <div>
                                    <label for="origin_place_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tempat Asal <span class="text-red-500">*</span>
                                    </label>
                                    <select name="origin_place_id" id="origin_place_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih Tempat</option>
                                        @foreach($orgPlaces as $place)
                                            <option value="{{ $place->id }}" {{ old('origin_place_id', $notaDinas->origin_place_id) == $place->id ? 'selected' : '' }}>
                                                {{ $place->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors && $errors->has('origin_place_id'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('origin_place_id') }}</p>
                                    @endif
                                </div>

                                <!-- Sifat -->
                                <div>
                                    <label for="sifat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Sifat <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sifat" id="sifat" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="Penting" {{ old('sifat', $notaDinas->sifat) == 'Penting' ? 'selected' : '' }}>Penting</option>
                                        <option value="Segera" {{ old('sifat', $notaDinas->sifat) == 'Segera' ? 'selected' : '' }}>Segera</option>
                                        <option value="Biasa" {{ old('sifat', $notaDinas->sifat) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                        <option value="Rahasia" {{ old('sifat', $notaDinas->sifat) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                    </select>
                                    @if ($errors && $errors->has('sifat'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('sifat') }}</p>
                                    @endif
                                </div>

                                <!-- Tanggal Nota Dinas -->
                                <div>
                                    <label for="nd_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal Nota Dinas <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="nd_date" id="nd_date" value="{{ old('nd_date', $notaDinas->nd_date ? (\Carbon\Carbon::parse($notaDinas->nd_date)->format('Y-m-d')) : '') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @if ($errors && $errors->has('nd_date'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('nd_date') }}</p>
                                    @endif
                                </div>

                                <!-- Hal -->
                                <div>
                                    <label for="hal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Hal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="hal" id="hal" placeholder="Masukkan hal surat" value="{{ old('hal', $notaDinas->hal) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @if ($errors && $errors->has('hal'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('hal') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Number Section -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Nomor Dokumen</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <!-- Manual Number Toggle -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="number_is_manual" value="1" id="number_is_manual" {{ old('number_is_manual', $notaDinas->number_is_manual) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="number_is_manual" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Gunakan nomor manual
                                    </label>
                                </div>

                                <!-- Manual Number Fields -->
                                <div id="manual-number-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
                                    <div>
                                        <label for="doc_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Nomor Dokumen <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="doc_no" id="doc_no" placeholder="Masukkan nomor dokumen" value="{{ old('doc_no', $notaDinas->doc_no) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @if ($errors && $errors->has('doc_no'))
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('doc_no') }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="number_manual_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Alasan Nomor Manual <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="number_manual_reason" id="number_manual_reason" placeholder="Masukkan alasan" value="{{ old('number_manual_reason', $notaDinas->number_manual_reason) }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @if ($errors && $errors->has('number_manual_reason'))
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('number_manual_reason') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Information -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Perjalanan</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Tanggal Mulai -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $notaDinas->start_date ? (\Carbon\Carbon::parse($notaDinas->start_date)->format('Y-m-d')) : '') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @if ($errors && $errors->has('start_date'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('start_date') }}</p>
                                    @endif
                                </div>

                                <!-- Tanggal Selesai -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $notaDinas->end_date ? (\Carbon\Carbon::parse($notaDinas->end_date)->format('Y-m-d')) : '') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @if ($errors && $errors->has('end_date'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('end_date') }}</p>
                                    @endif
                                </div>

                                <!-- Jenis Perjalanan -->
                                <div>
                                    <label for="trip_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Jenis Perjalanan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="trip_type" id="trip_type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="LUAR_DAERAH" {{ old('trip_type', $notaDinas->trip_type) == 'LUAR_DAERAH' ? 'selected' : '' }}>Luar Daerah</option>
                                        <option value="DALAM_DAERAH_GT8H" {{ old('trip_type', $notaDinas->trip_type) == 'DALAM_DAERAH_GT8H' ? 'selected' : '' }}>Dalam Daerah > 8 Jam</option>
                                        <option value="DALAM_DAERAH_LE8H" {{ old('trip_type', $notaDinas->trip_type) == 'DALAM_DAERAH_LE8H' ? 'selected' : '' }}>Dalam Daerah ≤ 8 Jam</option>
                                        <option value="DIKLAT" {{ old('trip_type', $notaDinas->trip_type) == 'DIKLAT' ? 'selected' : '' }}>Diklat</option>
                                    </select>
                                    @if ($errors && $errors->has('trip_type'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('trip_type') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Isi Surat</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-6">
                                <!-- Dasar -->
                                <div>
                                    <label for="dasar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Dasar <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="dasar" id="dasar" rows="3" placeholder="Masukkan dasar surat" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('dasar', $notaDinas->dasar) }}</textarea>
                                    @if ($errors && $errors->has('dasar'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('dasar') }}</p>
                                    @endif
                                </div>

                                <!-- Maksud -->
                                <div>
                                    <label for="maksud" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Maksud <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="maksud" id="maksud" rows="3" placeholder="Masukkan maksud surat" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('maksud', $notaDinas->maksud) }}</textarea>
                                    @if ($errors && $errors->has('maksud'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('maksud') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participants Section with Search -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Peserta Perjalanan Dinas</h2>
                        </div>
                        <div class="px-6 py-4">
                            <!-- Search Box -->
                            <div class="mb-4">
                                <label for="participant-search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Cari Peserta
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="participant-search" placeholder="Ketik nama pegawai untuk mencari..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <!-- Select All/None -->
                            <div class="mb-4 flex space-x-4">
                                <button type="button" id="select-all-participants" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                    Pilih Semua
                                </button>
                                <button type="button" id="deselect-all-participants" class="text-sm text-gray-600 hover:text-gray-500 font-medium">
                                    Batal Pilih Semua
                                </button>
                            </div>

                            <!-- Participants List -->
                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 max-h-80 overflow-y-auto">
                                <div id="participants-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($users as $user)
                                        <div class="participant-item flex items-start p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-name="{{ strtolower($user->fullNameWithTitles()) }}">
                                            <input type="checkbox" name="participants[]" value="{{ $user->id }}" id="participant-{{ $user->id }}" {{ in_array($user->id, old('participants', $notaDinas->participants->pluck('user_id')->toArray())) ? 'checked' : '' }} class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <div class="ml-3 flex-1">
                                                <label for="participant-{{ $user->id }}" class="block text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                                    {{ $user->fullNameWithTitles() }}
                                                </label>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $user->position?->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- No Results Message -->
                                <div id="no-results" class="hidden text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada pegawai ditemukan</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Coba ubah kata kunci pencarian Anda.</p>
                                </div>
                            </div>
                            
                            @if ($errors && $errors->has('participants'))
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('participants') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Tambahan</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Jumlah Lampiran -->
                                <div>
                                    <label for="lampiran_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Jumlah Lampiran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="lampiran_count" id="lampiran_count" value="{{ old('lampiran_count', $notaDinas->lampiran_count) }}" min="1" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @if ($errors && $errors->has('lampiran_count'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('lampiran_count') }}</p>
                                    @endif
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="DRAFT" {{ old('status', $notaDinas->status) == 'DRAFT' ? 'selected' : '' }}>Draft</option>
                                        <option value="APPROVED" {{ old('status', $notaDinas->status) == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                                    </select>
                                    @if ($errors && $errors->has('status'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('status') }}</p>
                                    @endif
                                </div>

                                <!-- Tembusan -->
                                <div>
                                    <label for="tembusan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tembusan
                                    </label>
                                    <textarea name="tembusan" id="tembusan" rows="2" placeholder="Masukkan tembusan (opsional)" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('tembusan', $notaDinas->tembusan) }}</textarea>
                                    @if ($errors && $errors->has('tembusan'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('tembusan') }}</p>
                                    @endif
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Catatan
                                    </label>
                                    <textarea name="notes" id="notes" rows="2" placeholder="Masukkan catatan (opsional)" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $notaDinas->notes) }}</textarea>
                                    @if ($errors && $errors->has('notes'))
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $errors->first('notes') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('documents') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Nota Dinas
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </flux:main>

    <!-- JavaScript for Interactive Features -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manual Number Toggle
            const manualToggle = document.getElementById('number_is_manual');
            const manualFields = document.getElementById('manual-number-fields');
            const docNoField = document.getElementById('doc_no');
            const reasonField = document.getElementById('number_manual_reason');

            function toggleManualFields() {
                if (manualToggle.checked) {
                    manualFields.style.display = 'block';
                    if (docNoField) docNoField.required = true;
                    if (reasonField) reasonField.required = true;
                } else {
                    manualFields.style.display = 'none';
                    if (docNoField) {
                        docNoField.required = false;
                        docNoField.value = '';
                    }
                    if (reasonField) {
                        reasonField.required = false;
                        reasonField.value = '';
                    }
                }
            }

            if (manualToggle) {
                manualToggle.addEventListener('change', toggleManualFields);
                toggleManualFields(); // Initial call
            }

            // Participant Search
            const searchInput = document.getElementById('participant-search');
            const participantItems = document.querySelectorAll('.participant-item');
            const noResults = document.getElementById('no-results');
            const selectAllBtn = document.getElementById('select-all-participants');
            const deselectAllBtn = document.getElementById('deselect-all-participants');

            function filterParticipants() {
                const searchTerm = searchInput.value.toLowerCase();
                let visibleCount = 0;

                participantItems.forEach(item => {
                    const name = item.getAttribute('data-name');
                    if (name.includes(searchTerm)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0 && searchTerm !== '') {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterParticipants);
            }

            // Select All Participants
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    const visibleCheckboxes = document.querySelectorAll('.participant-item:not([style*="display: none"]) input[type="checkbox"]');
                    visibleCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                });
            }

            // Deselect All Participants
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    const allCheckboxes = document.querySelectorAll('input[name="participants[]"]');
                    allCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });
            }
        });
    </script>
</x-layouts.app.sidebar>