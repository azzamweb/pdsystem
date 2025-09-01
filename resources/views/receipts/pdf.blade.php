<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi - {{ $receipt->receipt_no ?? 'Manual' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .doc-no {
            font-size: 12pt;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        .value {
            display: inline-block;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid black;
            margin-top: 50px;
            padding-top: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">KWITANSI</div>
        <div class="doc-no">Nomor: {{ $receipt->receipt_no ?? 'Manual' }}</div>
    </div>

    <div class="content">
        <!-- Informasi Dasar -->
        <div class="section">
            <div class="section-title">INFORMASI DASAR</div>
            <div class="info-row">
                <span class="label">Tanggal Kwitansi:</span>
                <span class="value">{{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->locale('id')->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Nomor Kwitansi (SIPD):</span>
                <span class="value">{{ $receipt->receipt_no ?: '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Kode Rekening Kegiatan:</span>
                <span class="value">{{ $receipt->account_code ?: '-' }}</span>
            </div>
        </div>

        <!-- Informasi SPPD -->
        <div class="section">
            <div class="section-title">INFORMASI SPPD</div>
            <div class="info-row">
                <span class="label">Nomor SPPD:</span>
                <span class="value">{{ $receipt->sppd->doc_no }}</span>
            </div>
            <div class="info-row">
                <span class="label">Penerima Pembayaran:</span>
                <span class="value">
                    {{ $receipt->payeeUser->gelar_depan ?? '' }} {{ $receipt->payeeUser->name ?? 'N/A' }} {{ $receipt->payeeUser->gelar_belakang ?? '' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label">NIP:</span>
                <span class="value">{{ $receipt->payeeUser->nip ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jabatan:</span>
                <span class="value">{{ $receipt->payeeUser->position?->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Unit Kerja:</span>
                <span class="value">{{ $receipt->payeeUser->unit?->name ?? '-' }}</span>
            </div>
        </div>

        <!-- Informasi Perjalanan -->
        <div class="section">
            <div class="section-title">INFORMASI PERJALANAN</div>
            <div class="info-row">
                <span class="label">Tujuan:</span>
                <span class="value">
                    {{ $receipt->sppd->spt?->notaDinas?->destinationCity?->name ?? 'N/A' }}, 
                    {{ $receipt->sppd->spt?->notaDinas?->destinationCity?->province?->name ?? 'N/A' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label">Durasi:</span>
                <span class="value">{{ $receipt->sppd->days_count ?? 0 }} hari</span>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="section">
            <div class="section-title">TOTAL PEMBAYARAN</div>
            <div class="info-row">
                <span class="label">Jumlah:</span>
                <span class="value text-bold">Rp {{ number_format($receipt->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-box">
                <div class="signature-line">
                    <div class="text-center">
                        <strong>Setuju Dibayar</strong><br>
                        {{ $receipt->sppd->signedByUser->gelar_depan ?? '' }} {{ $receipt->sppd->signedByUser->name ?? 'N/A' }} {{ $receipt->sppd->signedByUser->gelar_belakang ?? '' }}<br>
                        {{ $receipt->sppd->signedByUser->position?->name ?? '-' }}<br>
                        NIP. {{ $receipt->sppd->signedByUser->nip ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <div class="text-center">
                        <strong>{{ $receipt->treasurer_title ?? 'Bendahara' }}</strong><br>
                        {{ $receipt->getTreasurerUserSnapshot()['gelar_depan'] ?? '' }} {{ $receipt->getTreasurerUserSnapshot()['name'] ?? 'N/A' }} {{ $receipt->getTreasurerUserSnapshot()['gelar_belakang'] ?? '' }}<br>
                        {{ $receipt->getTreasurerUserSnapshot()['position_name'] ?? '-' }}<br>
                        NIP. {{ $receipt->getTreasurerUserSnapshot()['nip'] ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
