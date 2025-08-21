<style>
    /* CSS untuk tampilan web saja */
</style>

<div class="space-y-6">
    <!-- Tombol aksi tetap di atas -->
    <div class="flex justify-between items-center no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Nota Dinas</h1>
                            <p class="text-gray-600 dark:text-gray-400">Nomor: {{ $notaDinas->doc_no }}</p>
            <div class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($notaDinas->status === 'DRAFT')
                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                    @elseif($notaDinas->status === 'APPROVED')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else
                        bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-200
                    @endif
                ">
                    Status: {{ $notaDinas->status }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('nota-dinas.edit', $notaDinas) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('nota-dinas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Surat Layout -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 mx-auto border border-gray-200 dark:border-gray-700 text-sm" style="width: 800px; max-width: 100%;">
        <!-- Tombol Cetak -->
        <div class="mb-4 flex justify-end">
            <a href="{{ route('nota-dinas.pdf', $notaDinas->id) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Preview PDF
            </a>
        </div>
        
        <!-- Header Instansi -->
        <table class="w-full mb-10 pb-2" style="border-bottom: 2px solid black;">
            <tr>
                <td style="width: 100px; vertical-align: top; ">
                    <img src="/logobengkalis.png" alt="Logo" style="width: 80px; margin-bottom: 10px;">
                </td>
                <td style="text-align: center; vertical-align: top;">
                    <div class="font-bold text-md md:text-xl uppercase">PEMERINTAH KABUPATEN BENGKALIS</div>
                    <div class="font-bold text-lg md:text-xl uppercase">{{ \DB::table('org_settings')->value('name') }}</div>
                    <div class="text-xs md:text-sm">{{ \DB::table('org_settings')->value('address') }}</div>
                    <div class="text-xs md:text-sm">Telepon {{ \DB::table('org_settings')->value('phone') }} e-mail : {{ \DB::table('org_settings')->value('email') }}</div>
                </td>
                <td style="width: 100px;"></td>
            </tr>
        </table>
        <div class="text-center  pb-2 mb-4">
            <div class="font-bold text-base md:text-lg mt-2">NOTA DINAS</div>
        </div>
        <!-- Info Surat Dua Kolom -->
        <div class="grid grid-cols-1 md:grid-cols-1 gap-x-8 gap-y-2 mb-6">
            <table class="w-full text-sm" style="table-layout: fixed;">
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Yth.</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->toUser?->position?->name ?? '-' }} {{ \DB::table('org_settings')->value('name') }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Dari</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->fromUser?->position?->name ?? '-' }} {{ $notaDinas->fromUser?->unit?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Tembusan</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->tembusan ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Tanggal</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->nd_date ? \Carbon\Carbon::parse($notaDinas->nd_date)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Nomor</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->doc_no }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Sifat</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->sifat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Lampiran</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->lampiran_count ? $notaDinas->lampiran_count.' lembar' : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 80px;" class="font-semibold align-top">Hal</td>
                    <td style="width: 20px;" class="align-top">:</td>
                    <td class="align-top pl-2">{{ $notaDinas->hal }}</td>
                </tr>
            </table>
        </div>
        <div class="border-b-2 border-black mb-4"></div>
        <!-- Isi Surat -->
        <div class="mb-4 text-justify text-sm">
            <p>Bersama ini diajukan rencana Perjalanan Dinas kepada Bapak {{ \DB::table('org_settings')->value('head_title') }} {{ \DB::table('org_settings')->value('name') }} dengan ketentuan sebagai berikut :</p>
        </div>
        <div class="mb-4">
            <table class="w-full text-sm" style="table-layout: fixed;">
                <tr>
                    <td style="width: 30px; vertical-align: top;" class="align-top">1.</td>
                    <td style="width: 150px; vertical-align: top;" class="font-semibold align-top">Dasar</td>
                    <td style="width: 20px; vertical-align: top;" class="align-top">:</td>
                    <td style="vertical-align: top;" class="align-top pl-2">{{ $notaDinas->dasar }}</td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top;" class="align-top">2.</td>
                    <td style="width: 150px; vertical-align: top;" class="font-semibold align-top">Maksud</td>
                    <td style="width: 20px; vertical-align: top;" class="align-top">:</td>
                    <td style="vertical-align: top;" class="align-top pl-2">{{ $notaDinas->maksud }}</td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top;" class="align-top">3.</td>
                    <td style="width: 150px; vertical-align: top;" class="font-semibold align-top">Tujuan</td>
                    <td style="width: 20px; vertical-align: top;" class="align-top">:</td>
                    <td style="vertical-align: top;" class="align-top pl-2">{{ $notaDinas->destinationCity?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top;" class="align-top">4.</td>
                    <td style="width: 150px; vertical-align: top;" class="font-semibold align-top">Lamanya Perjalanan</td>
                    <td style="width: 20px; vertical-align: top;" class="align-top">:</td>
                    <td style="vertical-align: top;" class="align-top pl-2">{{ $notaDinas->days_count }} hari PP dari Tgl. {{ $notaDinas->start_date ? \Carbon\Carbon::parse($notaDinas->start_date)->locale('id')->translatedFormat('d F Y') : '-' }} s/d {{ $notaDinas->end_date ? \Carbon\Carbon::parse($notaDinas->end_date)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="width: 30px; vertical-align: top;" class="align-top">5.</td>
                    <td style="width: 150px; vertical-align: top;" class="font-semibold align-top">Yang Bepergian</td>
                    <td style="width: 20px; vertical-align: top;" class="align-top">:</td>
                    <td style="vertical-align: top;" class="align-top pl-2"></td>
                </tr>
            </table>
        </div>
        <!-- Tabel Peserta -->
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full border border-gray-400 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 w-8">No</th>
                        <th class="border px-2 py-1">Nama/NIP/Pangkat</th>
                        <th class="border px-2 py-1">Jabatan</th>
                        <th class="border px-2 py-1">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participantsOrdered as $i => $p)
                        <tr>
                            <td class="border px-2 py-1 text-center">{{ $i+1 }}</td>
                            <td class="border px-2 py-1">
                                {{ $p->user->name ?? '-' }}<br>
                                {{ $p->user->rank?->name ?? '-' }} ({{ $p->user->rank?->code ?? '-' }})<br>
                                NIP {{ $p->user->nip ?? '-' }}
                            </td>
                            <td class="border px-2 py-1">{{ $p->user->position_desc ?: ($p->user->position?->name ?? '-') }}</td>
                            <td class="border px-2 py-1"></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="border px-2 py-1 text-center text-gray-400">Tidak ada peserta</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Penutup -->
        <div class="mb-8 mt-4 text-sm">Demikian disampaikan, atas bantuan dan persetujuan Bapak diucapkan terima kasih.</div>
        <!-- Tanda Tangan -->
        <br>
        <br>
        <div class="flex justify-end mt-8">
            <div class="text-left text-sm">
                <div class="mb-1">{{ $notaDinas->fromUser?->position?->name ?? '-' }} {{ $notaDinas->fromUser?->unit?->name ?? '-' }}</div>
                <div class="mb-1">{{ \DB::table('org_settings')->value('name') }}</div>
                <div class="mb-1">Kabupaten Bengkalis</div>
                <br><br>
                <div class="font-bold underline">{{ $notaDinas->fromUser?->gelar_depan ?? '-' }} {{ $notaDinas->fromUser?->name ?? '-' }} {{ $notaDinas->fromUser?->gelar_belakang ?? '-' }}</div>
                <div>{{ $notaDinas->fromUser?->rank?->name ?? '-' }} ({{ $notaDinas->fromUser?->rank?->code ?? '-' }})</div>
                <div>NIP. {{ $notaDinas->fromUser?->nip ?? '-' }}</div>
            </div>
        </div>
    </div>
    <!-- Section Aksi Dokumen: Perubahan Status & SPT/SPPD -->
    <div class="mt-8 grid grid-cols-1 gap-6 no-print">
        <!-- Perubahan Status diarahkan ke halaman Edit -->
        {{-- <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Perubahan Status Nota Dinas
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Perubahan status sekarang dilakukan melalui halaman Edit agar lebih konsisten dan aman.</p>
            <a href="{{ route('nota-dinas.edit', $notaDinas) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Ubah Status di Halaman Edit
            </a>
        </div> --}}
        <!-- Tombol SPT/SPPD -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Aksi Dokumen SPT / SPPD
            </h3>
            @if($notaDinas->status === 'APPROVED')
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="{{ route('spt.create', ['nota_dinas_id' => $notaDinas->id]) }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Buat SPT</span>
                    </a>
                    @if($notaDinas->spt)
                        <a href="{{ route('spt.show', $notaDinas->spt) }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Lihat SPT</span>
                        </a>
                    @endif
                    @if($notaDinas->spt)
                        <a href="{{ route('sppd.create', ['spt_id' => $notaDinas->spt->id]) }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-md bg-cyan-600 text-white hover:bg-cyan-700 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Buat SPPD</span>
                        </a>
                    @else
                        <button class="flex items-center gap-2 px-4 py-2 rounded-md bg-cyan-400 text-white opacity-60 cursor-not-allowed" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Buat SPPD</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="text-gray-400 text-xs">Aksi SPT/SPPD hanya tersedia jika status Nota Dinas sudah APPROVED.</div>
            @endif
        </div>
    </div>
</div>

<script>
function printDocument() {
    // Simpan elemen yang tidak perlu dicetak
    const noPrintElements = document.querySelectorAll('.no-print, .print-button');
    const originalDisplay = [];
    
    // Sembunyikan elemen yang tidak perlu dicetak
    noPrintElements.forEach((el, index) => {
        originalDisplay[index] = el.style.display;
        el.style.display = 'none';
    });
    
    // Buat iframe untuk print yang bersih
    const printFrame = document.createElement('iframe');
    printFrame.style.position = 'absolute';
    printFrame.style.left = '-9999px';
    printFrame.style.top = '-9999px';
    document.body.appendChild(printFrame);
    
    const printDocument = printFrame.contentDocument || printFrame.contentWindow.document;
    const printWindow = printFrame.contentWindow || printFrame;
    
    // Ambil konten dokumen
    const documentContent = document.querySelector('.print-container').cloneNode(true);
    
    // Tambahkan CSS untuk print
    const printCSS = `
        <style>
            @page {
                size: A4 portrait;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
                background: white;
            }
            .print-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 15mm;
                background: white;
                box-sizing: border-box;
            }
            table {
                page-break-inside: avoid;
                border-collapse: collapse;
                width: 100%;
                table-layout: fixed;
            }
            td, th {
                padding: 2pt;
                vertical-align: top;
            }
            /* Border hanya untuk tabel peserta */
            .border, .border-gray-400 {
                border: 1px solid #000 !important;
            }
            /* Border bawah kop surat */
            .border-b-2, .border-b {
                border-bottom: 2px solid #000 !important;
            }
            .bg-gray-100 {
                background-color: #f3f4f6 !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-left {
                text-align: left !important;
            }
            .text-justify {
                text-align: justify !important;
            }
            .font-semibold {
                font-weight: 600 !important;
            }
            .font-bold {
                font-weight: bold !important;
            }
            .underline {
                text-decoration: underline !important;
            }
            .pl-2 {
                padding-left: 8pt !important;
            }
            .px-2 {
                padding-left: 8pt !important;
                padding-right: 8pt !important;
            }
            .py-1 {
                padding-top: 4pt !important;
                padding-bottom: 4pt !important;
            }
            .w-8 {
                width: 32pt !important;
            }
            .mb-1, .mb-2, .mb-4, .mb-8 {
                margin-bottom: 4pt !important;
            }
            .mt-4, .mt-8 {
                margin-top: 4pt !important;
            }
            .flex {
                display: flex !important;
            }
            .justify-end {
                justify-content: flex-end !important;
            }
            .items-center {
                align-items: center !important;
            }
            .gap-2 {
                gap: 8pt !important;
            }
            img {
                max-width: 100%;
                height: auto;
            }
            .overflow-x-auto {
                overflow: visible !important;
            }
            .min-w-full {
                min-width: 100% !important;
            }
        </style>
    `;
    
    printDocument.open();
    printDocument.write(printCSS);
    printDocument.write(documentContent.outerHTML);
    printDocument.close();
    
    // Tunggu konten dimuat lalu print
    printFrame.onload = function() {
        printWindow.print();
        
        // Bersihkan setelah print
        setTimeout(() => {
            document.body.removeChild(printFrame);
            
            // Kembalikan elemen yang disembunyikan
            noPrintElements.forEach((el, index) => {
                el.style.display = originalDisplay[index];
            });
        }, 1000);
    };
}
</script>
