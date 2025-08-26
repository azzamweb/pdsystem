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
            table-layout: fixed;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            vertical-align: middle;
            font-size: 7px;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 8px;
        }
        
        .name-cell {
            text-align: left;
            width: 160px;
            max-width: 160px;
        }
        
        .position-cell {
            text-align: left;
            width: 90px;
            max-width: 90px;
            font-size: 7px;
            line-height: 1.2;
        }
        
        .unit-cell {
            text-align: left;
            width: 70px;
            max-width: 70px;
            font-size: 7px;
            line-height: 1.2;
        }
        
        .date-cell {
            width: 16px;
            min-width: 16px;
            max-width: 16px;
        }
        
        .weekend {
            background-color: #ffcccc;
        }
        
        .assignment {
            background-color: #cce5ff;
        }
        
        .name-info {
            margin: 0;
            padding: 0;
        }
        
        .name-info .full-name {
            font-weight: bold;
            font-size: 7px;
            margin: 0;
            line-height: 1.2;
        }
        
        .name-info .nip {
            font-size: 6px;
            margin: 1px 0;
            line-height: 1.1;
        }
        
        .name-info .rank {
            font-size: 6px;
            margin: 1px 0;
            line-height: 1.1;
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
                <th style="width: 20px;">No</th>
                <th class="name-cell">Nama Lengkap, NIP, Pangkat</th>
                <th class="position-cell">Jabatan</th>
                <th class="unit-cell">Unit</th>
                @for($day = 1; $day <= $daysInMonth; $day++)
                    <th class="date-cell">{{ $day }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="name-cell">
                        <div class="name-info">
                            <div class="full-name">{{ $p->fullNameWithTitles() }}</div>
                            <div class="nip">NIP: {{ $p->nip ?? '-' }}</div>
                            <div class="rank">{{ $p->rank->name ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="position-cell">{{ $p->position->name ?? '-' }}</td>
                    <td class="unit-cell">{{ $p->unit->name ?? '-' }}</td>
                    
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
                        
                        <td class="date-cell {{ $cellClass }}">
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
