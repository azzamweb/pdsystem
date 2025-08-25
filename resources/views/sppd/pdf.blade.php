<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjalanan Dinas - {{ $sppd->doc_no }}</title>
    <style>
        @page { size: A4; margin-right: 20mm; margin-left: 20mm; margin-top: 15mm; margin-bottom: 10mm; }
        @page:first { margin-right: 20mm; margin-left: 20mm; margin-top: 5mm; margin-bottom: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 12pt; line-height: 1.4; margin: 0; padding: 0; }
        p, h1, h2, h3, h4, h5, h6, table, th, td, li { line-height: 1; }
        .container { width: 100%; margin: 0; padding: 0; }
        
        /* Header - sama dengan Nota Dinas dan SPT */
        .header { text-align: center; margin-bottom: 3mm; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: middle; padding: 0; }
        .logo { height: 18mm; max-width: 100%; }
        .header-text { text-align: center; }
        .header-text h1 { font-size: 13pt; margin: 0 0 1pt 0; text-transform: uppercase; letter-spacing: 0.5pt; }
        .header-text h3 { font-size: 11pt; margin: 0 0 1pt 0; text-transform: uppercase; letter-spacing: 0.5pt; }
        .header-text .unit { font-size: 11pt; font-weight: 700; margin: 0 0 2pt 0; text-transform: uppercase; }
        .header-text p { font-size: 8pt; margin: 1pt 0; }
        
        /* Document Numbering Section */
        .doc-numbering { 
            text-align: right; 
            margin-bottom: 3mm; 
            font-size: 10pt;
        }
        .doc-numbering table { 
            width: auto; 
            margin-left: auto; 
            border-collapse: collapse; 
        }
        .doc-numbering td { 
            padding: 1pt 0; 
            vertical-align: top; 
        }
        .doc-numbering .label { 
            padding-right: 8pt; 
        }
        .doc-numbering .separator { 
            padding-right: 4pt; 
        }
        .doc-numbering .content { 
            font-weight: bold; 
        }
        
        /* Document Title - sama dengan Nota Dinas dan SPT */
        .document-title { font-size: 12pt; font-weight: bold; text-align: center; margin: 2mm 0 4mm 0; }
        
        /* Main Table untuk SPD */
        .main-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 4mm; 
            border: 1px solid black;
        }
        .main-table td { 
            padding: 2pt; 
            vertical-align: top; 
            border: 1px solid black;
        }
        .main-table .number { 
            width: 20pt; 
            text-align: center; 
            font-weight: bold;
            
            font-size: 11pt;
        }
        .main-table .label { 
            width: 180pt; 
            font-weight: normal;
           
            font-size: 11pt;
        }
        .main-table .content { 
            padding-left: 3pt; 
            font-size: 11pt;
        }
        
        /* Signature - sama dengan Nota Dinas dan SPT */
        .signature { margin-top: 6mm; page-break-inside: avoid; text-align: right; }
        .signature .block { display: inline-block; text-align: left; }
        .signature div { margin-bottom: 1pt; line-height: 1; }
        .signature .name { font-weight: bold; text-decoration: underline; }
        .signature .rank, .signature .nip { font-size: 11pt; line-height: 1; }
        
        /* Kontrol page-break agar rapi di multi-halaman */
        table { page-break-inside: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        .end-section { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header - sama dengan Nota Dinas dan SPT -->
        <div class="header">
            <table style="border-bottom: 2px solid black;">
                <tr>
                    <td style="width: 22mm;">
                        <img src="{{ public_path('logobengkalis.png') }}" alt="Logo" class="logo">
                    </td>
                    <td class="header-text">
                        <h3>PEMERINTAH KABUPATEN BENGKALIS</h3>
                        <h1>{{ \DB::table('org_settings')->value('name') }}</h1>
                        <p>{{ \DB::table('org_settings')->value('address') }}</p>
                        <p>Telepon {{ \DB::table('org_settings')->value('phone') }} e-mail : {{ \DB::table('org_settings')->value('email') }}</p>
                    </td>
                    <td style="width: 22mm;"></td>
                </tr>
            </table>
        </div>
        
        <!-- Document Numbering Section -->
        <div class="doc-numbering">
            <table>
                <tr>
                    <td class="label">Lembar ke</td>
                    <td class="separator">:</td>
                    <td class="content"></td>
                </tr>
                <tr>
                    <td class="label">Kode No.</td>
                    <td class="separator">:</td>
                    <td class="content"></td>
                </tr>
                <tr>
                    <td class="label">Nomor</td>
                    <td class="separator">:</td>
                    <td class="content">{{ $sppd->doc_no }}</td>
                </tr>
            </table>
        </div>
        
        <div class="document-title">SURAT PERJALANAN DINAS (SPD)</div>
        
        <!-- Main Table -->
        <table class="main-table">
            <tr>
                <td class="number">1.</td>
                <td class="label">Pejabat Pembuat Komitmen</td>
                <td class="content">{{ $sppd->spt?->notaDinas?->toUser?->position?->name ?? '-' }} {{ $sppd->spt?->notaDinas?->toUser?->unit?->name ?? ' ' }}{!! $sppd->spt?->notaDinas?->toUser?->unit?->name ? '<br>' : '' !!} {{ \DB::table('org_settings')->value('name') }}</td>
            </tr>
            <tr>
                <td class="number">2.</td>
                <td class="label">Nama / NIP Pegawai yang melaksanakan perjalanan dinas</td>
                <td class="content">
                    <strong>Nama</strong> : {{ $sppd->user->name ?? '-' }}<br>
                    <strong>NIP</strong> : {{ $sppd->user->nip ?? '-' }}
                </td>
            </tr>
            <tr>
                <td class="number">3.</td>
                <td class="label">a. Pangkat dan Golongan</td>
                <td class="content">{{ $sppd->user->rank?->name ?? '-' }} ({{ $sppd->user->rank?->code ?? '-' }})</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">b. Jabatan/Instansi</td>
                <td class="content">{{ $sppd->user->position_desc ?: ($sppd->user->position?->name ?? '-') }} {{ $sppd->user->unit?->name ?? '' }}</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">c. Tingkat Biaya Perjalanan Dinas</td>
                <td class="content">{{ $sppd->user->rank?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">4.</td>
                <td class="label">Maksud Perjalanan</td>
                <td class="content" style="text-align: justify; padding-bottom: 10px;">
                    @if($sppd->spt && $sppd->spt->notaDinas)
                        {{ $sppd->spt->notaDinas->maksud }}
                    @else
                        Melaksanakan perjalanan dinas sesuai dengan tugas yang diberikan
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number">5.</td>
                <td class="label">Alat angkut yang dipergunakan</td>
                <td class="content">
                    @if($sppd->transportModes && $sppd->transportModes->count() > 0)
                        {{ $sppd->transportModes->pluck('name')->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number">6.</td>
                <td class="label">a. Tempat berangkat</td>
                <td class="content">{{ $sppd->originPlace?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">b. Tempat tujuan</td>
                <td class="content">{{ $sppd->destinationCity?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="number">7.</td>
                <td class="label">a. Lamanya Perjalanan Dinas</td>
                <td class="content">
                    @if($sppd->spt && $sppd->spt->notaDinas)
                        {{ $sppd->spt->notaDinas->start_date && $sppd->spt->notaDinas->end_date ? \Carbon\Carbon::parse($sppd->spt->notaDinas->start_date)->diffInDays(\Carbon\Carbon::parse($sppd->spt->notaDinas->end_date)) + 1 : '-' }} ({{ $sppd->spt->notaDinas->start_date && $sppd->spt->notaDinas->end_date ? ucfirst(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format(\Carbon\Carbon::parse($sppd->spt->notaDinas->start_date)->diffInDays(\Carbon\Carbon::parse($sppd->spt->notaDinas->end_date)) + 1)) : '-' }}) hari PP
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">b. Tanggal berangkat</td>
                <td class="content">
                    @if($sppd->spt && $sppd->spt->notaDinas)
                        {{ $sppd->spt->notaDinas->start_date ? \Carbon\Carbon::parse($sppd->spt->notaDinas->start_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">c. Tanggal harus kembali</td>
                <td class="content">
                    @if($sppd->spt && $sppd->spt->notaDinas)
                        {{ $sppd->spt->notaDinas->end_date ? \Carbon\Carbon::parse($sppd->spt->notaDinas->end_date)->locale('id')->translatedFormat('d F Y') : '-' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="number">8.</td>
                <td class="content" colspan="2">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <thead>
                            <tr>
                                <th style="text-align: left; font-weight: normal; font-size: 9pt; border: none;">Pengikut: Nama</th>
                                <th style="text-align: center; font-weight: normal; font-size: 9pt; border: none;">Tanggal Lahir</th>
                                <th style="text-align: center; font-weight: normal; font-size: 9pt; border: none;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left; font-size: 9pt; border: none;">1.</td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 9pt; border: none;">2.</td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 9pt; border: none;">3.</td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 9pt; border: none;">4.</td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                                <td style="text-align: left; font-size: 9pt; border: none;"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="number">9.</td>
                <td class="label">a. Instansi</td>
                <td class="content">{{ \DB::table('org_settings')->value('name') }}</td>
            </tr>
            <tr>
                <td class="number"></td>
                <td class="label">b. Akun</td>
                <td class="content"></td>
            </tr>
            <tr>
                <td class="number">10.</td>
                <td class="label">Keterangan lain-lain</td>
                <td class="content"></td>
            </tr>
        </table>
        
        <!-- Signature - sama dengan Nota Dinas dan SPT -->
        <div class="signature end-section">
            <div class="block">
                <div>Di Keluarkan di Bengkalis</div>
                <div>Tanggal {{ $sppd->sppd_date ? \Carbon\Carbon::parse($sppd->sppd_date)->locale('id')->translatedFormat('d F Y') : '-' }}</div>
                <div>{{ $sppd->spt?->notaDinas?->fromUser?->position?->name ?? '-' }}</div>
                <div>{{ \DB::table('org_settings')->value('name') }}</div>
                <div>Selaku Kuasa Pengguna Anggaran</div>
                <br><br><br><br><br><br>
                <div class="name">{{ $sppd->spt?->notaDinas?->fromUser?->gelar_depan ?? '' }} {{ $sppd->spt?->notaDinas?->fromUser?->name ?? '-' }} {{ $sppd->spt?->notaDinas?->fromUser?->gelar_belakang ?? '' }}</div>
                <div class="rank">{{ $sppd->spt?->notaDinas?->fromUser?->rank?->name ?? '-' }} ({{ $sppd->spt?->notaDinas?->fromUser?->rank?->code ?? '-' }})</div>
                <div class="nip">NIP. {{ $sppd->spt?->notaDinas?->fromUser?->nip ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Halaman Kedua - Visum Penandatanganan SPPD -->
    <div class="container" style="page-break-before: always;">
        <!-- Travel Details Table -->
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 4mm;">
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <!-- kiri baris 1: kosong sesuai contoh -->
                    <div style="height: 40px;"></div>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="4">I.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Berangkat dari (tempat kedudukan)</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">{{ $sppd->originPlace->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Ke</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">{{ $sppd->destinationCity->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">{{ $sppd->spt?->notaDinas?->start_date ? \Carbon\Carbon::parse($sppd->spt->notaDinas->start_date)->locale('id')->translatedFormat('d F Y') : ($sppd->start_date ? \Carbon\Carbon::parse($sppd->start_date)->locale('id')->translatedFormat('d F Y') : '-') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- ROW 2 -->
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="3">II.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Tiba di</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Berangkat dari</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Ke</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- ROW 3 -->
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="3">III.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Tiba di</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Berangkat dari</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Ke</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- ROW 4 -->
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="3">IV.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Tiba di</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Berangkat dari</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Ke</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- ROW 5 -->
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="3">V.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Tiba di</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Berangkat dari</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Ke</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 40px;"></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- ROW 6 -->
            <tr >
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;" rowspan="3">VI.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Tiba di</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Pada tanggal</td>
                            <td style="width: 8px; text-align: center; vertical-align: top; font-size: 10pt;">:</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height: 0px;"></td>
                        </tr>
                    </table>
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <div style="line-height: 1.3; text-align: justify; font-size: 9pt;">
                        Telah diperiksa, dengan keterangan bahwa perjalanan tersebut di atas
                        benar dilakukan atas perintah dan semata‑mata untuk kepentingan jabatan
                        dalam waktu yang sesingkat‑singkatnya.
                    </div>
                    <div style="height: 0px;"></div>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    <div class="block">
                        <div>{{ $sppd->spt?->notaDinas?->fromUser?->position?->name ?? '-' }}</div>
                        <div>{{ \DB::table('org_settings')->value('name') }}</div>
                        <div>Selaku Kuasa Pengguna Anggaran</div>
                        <br><br><br><br>
                        <div class="name">{{ $sppd->spt?->notaDinas?->fromUser?->gelar_depan ?? '' }} {{ $sppd->spt?->notaDinas?->fromUser?->name ?? '-' }} {{ $sppd->spt?->notaDinas?->fromUser?->gelar_belakang ?? '' }}</div>
                        <div class="rank">{{ $sppd->spt?->notaDinas?->fromUser?->rank?->name ?? '-' }} ({{ $sppd->spt?->notaDinas?->fromUser?->rank?->code ?? '-' }})</div>
                        <div class="nip">NIP. {{ $sppd->spt?->notaDinas?->fromUser?->nip ?? '-' }}</div>
                    </div>
                
            <div style="height: 10px;"></div> 
                </td>
                <td style="border: 1px solid #000; vertical-align: top; padding: 4px; width: 50%;">
                    
                            <div class="block">
                                <div>{{ $sppd->spt?->notaDinas?->fromUser?->position?->name ?? '-' }}</div>
                                <div>{{ \DB::table('org_settings')->value('name') }}</div>
                                <div>Selaku Kuasa Pengguna Anggaran</div>
                                <br><br><br><br>
                                <div class="name">{{ $sppd->spt?->notaDinas?->fromUser?->gelar_depan ?? '' }} {{ $sppd->spt?->notaDinas?->fromUser?->name ?? '-' }} {{ $sppd->spt?->notaDinas?->fromUser?->gelar_belakang ?? '' }}</div>
                                <div class="rank">{{ $sppd->spt?->notaDinas?->fromUser?->rank?->name ?? '-' }} ({{ $sppd->spt?->notaDinas?->fromUser?->rank?->code ?? '-' }})</div>
                                <div class="nip">NIP. {{ $sppd->spt?->notaDinas?->fromUser?->nip ?? '-' }}</div>
                            </div>
                        
                    <div style="height: 10px;"></div>
                </td>
            </tr>

            <!-- ROW 7 -->
            <tr>
                <td colspan="2" style="border: 1px solid #000; vertical-align: top; padding: 4px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;">VII.</td>
                            <td style="width: 150px; vertical-align: top; font-size: 10pt;">Catatan Lain‑Lain</td>
                            <td style="word-break: break-word; vertical-align: top; font-size: 10pt;"></td>
                        </tr>
                    </table>
                  
                </td>
            </tr>

            <!-- ROW 8 -->
            <tr>
                <td colspan="2" style="border: 1px solid #000; vertical-align: top; padding: 4px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 25px; font-weight: bold; white-space: nowrap; vertical-align: top; font-size: 10pt;">VIII.</td>
                            <td style="line-height: 1.3; text-align: justify; font-size: 9pt;">
                                <strong>PERHATIAN</strong><br/>
                                PPK yang menerbitkan SPD, pegawai yang melakukan perjalanan dinas, para pejabat
                                yang mengesahkan tanggal berangkat/tiba, serta Bendahara Pengeluaran bertanggung
                                jawab berdasarkan peraturan‑peraturan Keuangan Negara apabila negara menderita rugi
                                akibat kesalahan, kelalaian, dan kealpaannya.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        
    </div>
</body>
</html>
