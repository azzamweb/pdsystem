<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Referensi Tarif</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Kelola semua referensi tarif untuk perjalanan dinas
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    <!-- Data Tingkatan Perjalanan -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('travel-grades.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        Data Tingkatan Perjalanan
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Kelola tingkatan perjalanan dinas (Bupati, Eselon II, dll)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mapping Tingkatan Pegawai -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('user-travel-grade-maps.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        Mapping Tingkatan Pegawai
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Hubungkan pegawai dengan tingkatan perjalanan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Tarif Uang Harian -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('perdiem-rates.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        Data Tarif Uang Harian
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Kelola tarif uang harian per provinsi dan tingkatan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Batas Tarif Penginapan -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('lodging-caps.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        Data Batas Tarif Penginapan
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Kelola batas maksimal tarif penginapan
                                </p>
                            </div>
                        </div>
                    </div>

                                                  <!-- Data Tarif Representasi -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('representation-rates.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Tarif Representasi
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola tarif representasi untuk Bupati dan Eselon II
                                          </p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Data Referensi Transportasi Dalam Provinsi -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('intra-province-transport-refs.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Referensi Transportasi Dalam Provinsi
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola referensi tarif transportasi darat umum dalam provinsi
                                          </p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Data Referensi Transportasi Dalam Kecamatan -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-teal-100 dark:bg-teal-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('intra-district-transport-refs.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Referensi Transportasi Dalam Kecamatan
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola referensi tarif transportasi dari tempat kerja ke ibukota kecamatan
                                          </p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Data Referensi Transportasi Kendaraan Dinas -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('official-vehicle-transport-refs.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Referensi Transportasi Kendaraan Dinas
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola referensi tarif transportasi kendaraan dinas/operasional
                                          </p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Data Komponen At-Cost -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('at-cost-components.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Komponen At-Cost
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola komponen biaya at-cost (Ro-Ro, Parkir Inap, Tol, Rapid Test, Taksi)
                                          </p>
                                      </div>
                                  </div>
                              </div>

                              <!-- Data Referensi Tiket Pesawat -->
                              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                  <div class="flex items-center">
                                      <div class="flex-shrink-0">
                                          <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                              <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                              </svg>
                                          </div>
                                      </div>
                                      <div class="ml-4">
                                          <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                              <a href="{{ route('airfare-refs.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                  Data Referensi Tiket Pesawat
                                              </a>
                                          </h3>
                                          <p class="text-sm text-gray-500 dark:text-gray-400">
                                              Kelola referensi harga tiket pesawat untuk RAB/anggaran
                                          </p>
                                      </div>
                                  </div>
                              </div>
                </div>
            </div>

            <!-- Configuration Section -->
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Format Penomoran Dokumen -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('doc-number-formats.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        Format Penomoran Dokumen
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Kelola format penomoran untuk semua jenis dokumen perjalanan dinas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
