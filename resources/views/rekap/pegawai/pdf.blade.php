<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Pegawai - {{ $monthName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed !important;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            vertical-align: middle;
            font-size: 7px;
            overflow: hidden;
            word-wrap: break-word;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8px;
        }
        
        .name-cell {
            text-align: left;
            width: 280px;
            max-width: 280px;
        }
        
        .position-unit-cell {
            text-align: left;
            width: 120px;
            max-width: 120px;
            font-size: 8px;
            line-height: 1.3;
        }
        
        .date-cell {
            width: 15px;
            min-width: 15px;
            max-width: 15px;
        }
        
        .weekend {
            background-color: #ffcccc !important;
        }
        
        .assignment {
            background-color: #cce5ff !important;
        }
        
        .name-info {
            margin: 0;
            padding: 0;
        }
        
        .name-info .full-name {
            font-weight: bold;
            font-size: 9px;
            margin: 0;
            line-height: 1.3;
        }
        
        .name-info .nip {
            font-size: 8px;
            margin: 2px 0;
            line-height: 1.2;
        }
        
        .name-info .rank {
            font-size: 8px;
            margin: 2px 0;
            line-height: 1.2;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
        }
        
        .legend {
            margin-top: 15px;
            font-size: 9px;
        }
        
        .legend-item {
            display: inline-block;
            margin-right: 20px;
        }
        
        .legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAPITULASI JADWAL PERJALANAN DINAS PEGAWAI</h1>
        <p>Periode: {{ $monthName }}</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20px !important;">No</th>
                <th style="width: 250px !important; min-width: 250px !important; text-align: left;">Nama Lengkap, NIP, Pangkat</th>
                <th style="width: 100px !important; min-width: 100px !important; text-align: left;">Jabatan & Unit</th>
                @for($day = 1; $day <= $daysInMonth; $day++)
                    <th style="width: 12px !important; min-width: 12px !important; max-width: 12px !important; font-size: 6px !important;">{{ $day }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $index => $p)
                <tr>
                    <td style="width: 20px !important;">{{ $index + 1 }}</td>
                    <td style="width: 250px !important; min-width: 250px !important; text-align: left; padding: 3px;">
                        <div class="name-info">
                            <div class="full-name">{{ $p->fullNameWithTitles() }}</div>
                            <div class="nip">NIP: {{ $p->nip ?? '-' }}</div>
                            <div class="rank">{{ $p->rank->name ?? '-' }}</div>
                        </div>
                    </td>
                    <td style="width: 100px !important; min-width: 100px !important; text-align: left; padding: 3px;">
                        <div style="font-weight: bold; margin-bottom: 3px; font-size: 8px;">{{ $p->position->name ?? '-' }}</div>
                        <div style="font-size: 7px; color: #666; line-height: 1.2;">{{ $p->unit->name ?? '-' }}</div>
                    </td>
                    
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $currentDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, $day)->startOfDay();
                            $hasAssignment = false;
                            $isWeekend = $currentDate->isWeekend();
                            
                            if (isset($scheduleData[$p->id])) {
                                foreach ($scheduleData[$p->id] as $assignment) {
                                    if ($currentDate->gte($assignment['start_date']) && $currentDate->lte($assignment['end_date'])) {
                                        $hasAssignment = true;
                                        break;
                                    }
                                }
                            }
                            
                            $cellClass = '';
                            if ($hasAssignment) {
                                $cellClass = 'assignment';
                            } elseif ($isWeekend) {
                                $cellClass = 'weekend';
                            }
                        @endphp
                        
                        <td style="width: 15px !important; min-width: 15px !important; max-width: 15px !important; {{ $cellClass }}">
                            @if($hasAssignment)
                                ●
                            @endif
                        </td>
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <div class="legend-item">
            <span class="legend-color" style="background-color: #cce5ff;"></span>
            <span>Perjalanan Dinas</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #ffcccc;"></span>
            <span>Hari Libur (Sabtu/Minggu)</span>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem Perjalanan Dinas</p>
    </div>
</body>
</html>
