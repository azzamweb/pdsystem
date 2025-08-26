<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perjalanan Dinas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .content {
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            padding: 3px 0;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
        }
        .activities {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PERJALANAN DINAS</div>
        <div class="subtitle">PEMERINTAH KABUPATEN BENGKALIS</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">I. INFORMASI DASAR</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nomor SPT:</div>
                    <div class="info-value">{{ $tripReport->spt->doc_no ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomor Laporan:</div>
                    <div class="info-value">{{ $tripReport->report_no ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Laporan:</div>
                    <div class="info-value">{{ $tripReport->report_date ? \Carbon\Carbon::parse($tripReport->report_date)->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">II. INFORMASI PERJALANAN</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Tempat Asal:</div>
                    <div class="info-value">{{ $tripReport->spt->notaDinas->originPlace->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tempat Tujuan:</div>
                    <div class="info-value">{{ $tripReport->spt->notaDinas->destinationCity->name ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Berangkat:</div>
                    <div class="info-value">{{ $tripReport->spt->notaDinas->start_date ? \Carbon\Carbon::parse($tripReport->spt->notaDinas->start_date)->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Kembali:</div>
                    <div class="info-value">{{ $tripReport->spt->notaDinas->end_date ? \Carbon\Carbon::parse($tripReport->spt->notaDinas->end_date)->format('d/m/Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">III. KEGIATAN YANG DILAKUKAN</div>
            <div class="activities">
                {!! formatActivitiesForPdf($tripReport->activities) !!}
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Bengkalis, {{ $tripReport->report_date ? \Carbon\Carbon::parse($tripReport->report_date)->format('d/m/Y') : date('d/m/Y') }}</p>
            <p>Yang membuat laporan,</p>
            <br><br><br>
            <p><strong>{{ $tripReport->createdByUser->name ?? 'N/A' }}</strong></p>
            <p>NIP: {{ $tripReport->createdByUser->nip ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>
