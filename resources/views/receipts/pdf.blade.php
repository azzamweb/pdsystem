<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Kwitansi - {{ $receipt->receipt_no ?? 'Manual' }}</title>
  <style>
    @page { size: A4; margin: 15mm 12mm; }
    body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.3; color:#000; }
    .topbar{display:flex;justify-content:flex-end;font-size:10pt;margin-bottom:4px}
    .header{text-align:center;margin:1px 0 6px}
    .title{font-weight:bold;letter-spacing:4px;font-size:14pt}
    /* === BAGIAN KWITANSI (2 kolom menggunakan tabel) === */
    .kwitansi-table{width:100%;border-collapse:collapse;border:1px solid #000}
    .kwitansi-table td{border-right:1px solid #000;padding:6px;vertical-align:top}
    .kwitansi-table td:first-child{text-align:center;width:35%}
    .kwitansi-table td:last-child{border-right:none;width:65%}
    .row{display:table;width:100%}
    .col-label{font-weight:bold;font-size:10pt;width:8mm;display:table-cell}
    .col-content{display:table-cell;padding-left:4px}
    .block{margin-bottom:12px;min-height:80px;display:flex;flex-direction:column;justify-content:space-between}
    .block:last-child{margin-bottom:0}
    .block-title{font-weight:bold;margin-bottom:4px;font-size:10pt}
    .muted{font-size:9pt}
    .name{text-decoration:underline;font-weight:bold;font-size:10pt}
    .text-center{text-align:center}
    .text-right{text-align:right}
    .mt-4{margin-top:8px}
    .mt-6{margin-top:12px}
    .mt-8{margin-top:16px}
    .kv-line{display:table;width:100%}
    .kv-line > div{display:table-cell}
    .kv-line > div:first-child{width:25mm}
    .dots{display:inline-block;border-bottom:1px dotted #000;width:100%;height:.8em;vertical-align:bottom}
    .divider{height:1px;background-color:#000;margin:8px 0}
    /* === TABEL KOLOM KEDUA === */
    .right-table{width:100%;border:none}
    .right-table td{padding:4px 0;vertical-align:top;border:none;text-align:left}
    .right-table .label-cell{width:35mm;border:none;text-align:left}
    .right-table .content-cell{padding-left:8px;border:none;text-align:left}
    .right-table .amount-cell{text-align:left !important;padding-left:0 !important;width:auto !important}
    /* === TABEL PERHITUNGAN SPPD RAMPUNG === */
    table{width:100%;border-collapse:collapse}
    .table{margin-top:8px}
    .table th,.table td{border:1px solid #000;padding:4px;font-size:9pt;vertical-align:top}
    .table th{text-align:center;font-weight:bold}
    /* === TANDA TANGAN BAWAH === */
    .signatures{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px}
    .sign-box{text-align:center}
    .sign-place{margin:8px 0 32px}
  </style>
</head>
<body>
@php
  $nd = $receipt->sppd->spt?->notaDinas;
  $kota = $nd?->destinationCity?->name;
  $prov = $nd?->destinationCity?->province?->name;
  $maksud = $nd?->maksud;
  $tahun = $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->year : now()->year;
  $bulanTahun = $receipt->receipt_date
      ? \Carbon\Carbon::parse($receipt->receipt_date)->locale('id')->translatedFormat('F Y')
      : '-';
  function money_id($n){ return 'Rp ' . number_format((float)($n ?? 0), 0, ',', '.'); }

  // (opsional) penjumlahan otomatis per kategori jika kamu punya $receipt->lines
  $lines = collect($receipt->lines ?? []);
  $byCat = fn($keys)=>$lines->whereIn('category',(array)$keys)->sum('amount');
  $transport = [
    'laut'  => $byCat(['transport_laut']),
    'darat' => $byCat(['transport_darat']),
    'roro'  => $byCat(['transport_roro','transport_darat_roro']),
    'udara' => $byCat(['transport_udara']),
    'taksi' => $byCat(['transport_taksi']),
  ];
  $lodgingAmount = $byCat(['lodging']);
  $perDiemAmount = $byCat(['per_diem']);
  $reprAmount    = $byCat(['representation']);
  $lodgingNights = $lines->firstWhere('category','lodging')['qty'] ?? null;
  $perDiemDays   = $lines->firstWhere('category','per_diem')['qty'] ?? null;
  $perDiemRate   = $lines->firstWhere('category','per_diem')['rate'] ?? null;
  $fmtNights = $lodgingNights ? "({$lodgingNights} Malam)" : "(1 Malam)";
  $fmtPerDiem = ($perDiemDays && $perDiemRate) ? "({$perDiemDays} hari x ".money_id($perDiemRate).")" : "";
@endphp

  <div class="topbar">Kas No : {{ $receipt->receipt_no ?? '__________________' }}</div>
  <div class="header"><div class="title">K  W  I  T  A  N  S  I</div></div>

  <!-- === BAGIAN 1: KWITANSI (dua kolom menggunakan tabel) === -->
  <table class="kwitansi-table">
    <tr>
      <!-- Kiri: A, B, C saja -->
      <td>
        <!-- A -->
        <div class="block">
          <div class="row">
            <div class="col-label">A.</div>
            <div class="col-content">
              <div class="block-title">PEMBAYARAN TAHUN DINAS</div>
              <div>TAHUN ANGGARAN {{ $tahun }}</div>
              <div class="mt-4"></div>
              <div class="block-title">KODE REKENING</div>
              <div>{{ $receipt->account_code ?: '-' }}</div>
              <div class="mt-4"></div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- B -->
        <div class="block">
          <div class="row">
            <div class="col-label">B.</div>
            <div class="col-content">
              <div class="block-title">SETUJU DIBAYAR</div>
              <div class="muted">KUASA PENGGUNA ANGGARAN</div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="name">{{ $receipt->sppd->signedByUser->fullNameWithTitles() ?? '-' }}</div>
              <div class="muted">NIP. {{ $receipt->sppd->signedByUser->nip ?? '-' }}</div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- C -->
        <div class="block">
          <div class="row">
            <div class="col-label">C.</div>
            <div class="col-content">
              <div class="block-title">LUNAS DIBAYAR</div>
              <div class="muted">PADA TGL.</div>
              <div class="muted">{{ $receipt->treasurer_title ?? 'Bendahara Pengeluaran Pembantu' }}</div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="mt-6"></div>
              <div class="name">{{ $receipt->getTreasurerUserSnapshot()['name'] ?? ($receipt->treasurerUser?->fullNameWithTitles() ?? '-') }}</div>
              <div class="muted">NIP. {{ $receipt->getTreasurerUserSnapshot()['nip'] ?? ($receipt->treasurerUser?->nip ?? '-') }}</div>
            </div>
          </div>
        </div>
      </td>

      <!-- Kanan: SUDAH TERIMA DARI, UANG SEBESAR, Y A I T U, Tanggal/Nomor, "Yang Terima" -->
      <td>
        <!-- SUDAH TERIMA DARI -->
        <table class="right-table">
          <tr>
            <td class="label-cell">SUDAH TERIMA DARI</td>
       
            <td>: KUASA PENGGUNA ANGGARAN</td>
          </tr>
        </table>

        <div class="mt-4"></div>

        <!-- UANG SEBESAR -->
        <table class="right-table">
          <tr>
            <td class="label-cell">UANG SEBESAR</td>
           
            <td>: {{ money_id($receipt->total_amount) }} -</td>
          </tr>
          <tr>
            <td></td>
            <td class="content-cell">( {{ terbilang($receipt->total_amount) }} rupiah )</td>
          </tr>
        </table>

        <div class="mt-6"></div>

        <!-- Y A I T U -->
        <table class="right-table">
          <tr>
            <td class="label-cell">Y A I T U</td>
            <td class="content-cell">: Pembayaran Biaya Perjalanan Dinas ke {{ $kota ?? '-' }} An. {{ $receipt->payeeUser->fullNameWithTitles() ?? '-' }}  @if($maksud){{ $maksud }}  @endif</td>
          </tr>
         
        </table>

        <div class="mt-6"></div>

        <!-- Tanggal dan Nomor -->
        <table class="right-table">
          <tr>
            <td class="label-cell">Tanggal</td>
            <td class="content-cell">: {{ $bulanTahun }}</td>
          </tr>
          <tr>
            <td class="label-cell">Nomor</td>
            <td class="content-cell">: {{ $receipt->sppd->doc_no ?? '-' }}</td>
          </tr>
        </table>

        <div class="mt-6"></div>

        <!-- Yang terima -->
        <div class="text-center">Yang terima</div>
        <div class="sign-place"></div>
        <div class="text-center name">{{ $receipt->payeeUser->fullNameWithTitles() ?? '-' }}</div>
        <div class="text-center muted">NIP. {{ $receipt->payeeUser->nip ?? '-' }}</div>
      </td>
    </tr>
  </table>

  <!-- === BAGIAN 2: PERHITUNGAN SPPD RAMPUNG (tabel terpisah) === -->
  <div class="mt-8 text-center" style="font-weight:bold;">PERHITUNGAN SPPD RAMPUNG</div>
  <table class="table">
    <thead>
      <tr>
        <th style="width:15mm;">No</th>
        <th>Uraian</th>
        <th style="width:25mm;">Uang Muka</th>
        <th style="width:30mm;">Jumlah Ditetapkan</th>
        <th style="width:30mm;">Lebih (Kurang)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center">1.</td>
        <td>
          Transportasi
          <div>- Laut</div>
          <div>- Darat</div>
          <div>- Darat/Roro</div>
          <div>- Udara</div>
          <div>- Taksi</div>
        </td>
        <td class="text-right"><div>-</div><div>-</div><div>-</div><div>-</div><div>-</div></td>
        <td class="text-right">
          <div>{{ $transport['laut']  ? money_id($transport['laut'])  : '-' }}</div>
          <div>{{ $transport['darat'] ? money_id($transport['darat']) : '-' }}</div>
          <div>{{ $transport['roro']  ? money_id($transport['roro'])  : '-' }}</div>
          <div>{{ $transport['udara'] ? money_id($transport['udara']) : '-' }}</div>
          <div>{{ $transport['taksi'] ? money_id($transport['taksi']) : '-' }}</div>
        </td>
        <td class="text-right"><div>-</div><div>-</div><div>-</div><div>-</div><div>-</div></td>
      </tr>

      <tr>
        <td class="text-center">2.</td>
        <td>Penginapan {{ $fmtNights }}</td>
        <td class="text-right">-</td>
        <td class="text-right">{{ $lodgingAmount ? money_id($lodgingAmount) : '-' }}</td>
        <td class="text-right">-</td>
      </tr>

      <tr>
        <td class="text-center">3.</td>
        <td>Uang Harian {!! $fmtPerDiem ? '<span class="block-title">'.$fmtPerDiem.'</span>' : '' !!}</td>
        <td class="text-right">-</td>
        <td class="text-right">{{ $perDiemAmount ? money_id($perDiemAmount) : '-' }}</td>
        <td class="text-right">-</td>
      </tr>

      <tr>
        <td class="text-center">4.</td>
        <td>Representatif</td>
        <td class="text-right">-</td>
        <td class="text-right">{{ $reprAmount ? money_id($reprAmount) : '-' }}</td>
        <td class="text-right">-</td>
      </tr>

      <tr>
        <td class="text-center" colspan="2" style="font-weight:bold;">JUMLAH</td>
        <td class="text-right">-</td>
        <td class="text-right" style="font-weight:bold;">{{ money_id($receipt->total_amount) }}</td>
        <td class="text-right">-</td>
      </tr>
    </tbody>
  </table>

  <!-- TTD bawah -->
  <div class="signatures">
    <div class="sign-box">
      <div>Mengetahui</div>
      <div class="muted">Pejabat Pelaksana Teknis Kegiatan</div>
      <div class="sign-place"></div>
      @php($pptk = $receipt->sppd->getPptkUserSnapshot())
      <div class="name">{{ ($pptk['name'] ?? null) ?: ($receipt->sppd->pptkUser?->fullNameWithTitles() ?? '-') }}</div>
      <div class="muted">NIP. {{ ($pptk['nip'] ?? null) ?: ($receipt->sppd->pptkUser?->nip ?? '-') }}</div>
    </div>
    <div class="sign-box">
      <div>Bengkalis, {{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->locale('id')->translatedFormat('d F Y') : '__________' }}</div>
      <div>Dihitung Oleh :</div>
      <div class="muted">{{ $receipt->treasurer_title ?? 'Bendahara Pengeluaran Pembantu' }}</div>
      <div class="sign-place"></div>
      <div class="name">{{ $receipt->getTreasurerUserSnapshot()['name'] ?? ($receipt->sppd->pptkUser?->fullNameWithTitles() ?? '-') }}</div>
      <div class="muted">NIP. {{ $receipt->getTreasurerUserSnapshot()['nip'] ?? ($receipt->sppd->pptkUser?->nip ?? '-') }}</div>
    </div>
  </div>
</body>
</html>
