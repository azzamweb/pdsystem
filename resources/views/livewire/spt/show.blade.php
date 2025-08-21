<div class="space-y-6">
    <div class="flex items-center justify-between no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Surat Perintah Tugas (SPT)</h1>
            <p class="text-gray-600 dark:text-gray-400">Nomor: <span class="font-mono">{{ $spt->doc_no }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('spt.edit', $spt) }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Edit</a>
            <a href="{{ $spt->nota_dinas_id ? route('nota-dinas.show', $spt->nota_dinas_id) : route('spt.index') }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">Kembali</a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 border border-gray-200 dark:border-gray-700 text-sm" style="width: 800px; max-width: 100%; margin: 0 auto;">
        <!-- Kop Instansi -->
        <table class="w-full mb-6" style="border-bottom: 2px solid #000;">
            <tr>
                <td style="width: 100px; vertical-align: top;">
                    <img src="/logobengkalis.png" alt="Logo" style="width: 80px;">
                </td>
                <td style="text-align: center;">
                    <div class="font-bold text-md md:text-xl uppercase">PEMERINTAH KABUPATEN BENGKALIS</div>
                    <div class="font-bold text-lg md:text-xl uppercase">{{ \DB::table('org_settings')->value('name') }}</div>
                    <div class="text-xs md:text-sm">{{ \DB::table('org_settings')->value('address') }}</div>
                    <div class="text-xs md:text-sm">Telepon {{ \DB::table('org_settings')->value('phone') }} e-mail : {{ \DB::table('org_settings')->value('email') }}</div>
                </td>
                <td style="width: 100px;"></td>
            </tr>
        </table>

        <!-- Judul Surat dan Nomor -->
        <div class="text-center mb-4">
            <div class="font-semibold">SURAT TUGAS</div>
            <div class="mt-1">NOMOR : <span class="font-mono">{{ $spt->doc_no }}</span></div>
        </div>

        <!-- Dasar -->
        <table class="w-full text-sm mb-4" style="table-layout: fixed;">
            <tr>
                <td style="width: 80px; vertical-align: top;">Dasar</td>
                <td style="width: 20px; vertical-align: top;">:</td>
                <td style="vertical-align: top;">
                    <table class="w-full">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">1.</td>
                            <td style="vertical-align: top;">Dokumen Pelaksanaan Anggaran Badan Pengelolaan Keuangan dan Aset Daerah Kabupaten Bengkalis</td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">2.</td>
                            <td style="vertical-align: top;">Nota Dinas {{ $spt->notaDinas?->fromUser?->position?->name }} {{ $spt->notaDinas?->requestingUnit?->name }} Nomor : {{ $spt->notaDinas?->doc_no }} Tanggal : {{ $spt->notaDinas?->nd_date ? \Carbon\Carbon::parse($spt->notaDinas->nd_date)->locale('id')->translatedFormat('F Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="text-center font-semibold mb-4">MEMERINTAHKAN</div>

        <!-- Kepada -->
        <table class="w-full text-sm mb-4" style="table-layout: fixed;">
            <tr>
                <td style="width: 80px; vertical-align:top;" class="align-top">Kepada</td>
                <td style="width: 20px; vertical-align:top;" class="align-top">:</td>
                <td class="align-top">
                    @php
                        $participants = $spt->notaDinas?->participants ?? collect();
                        $participants = $participants->sort(function ($a, $b) {
                            $ea = $a->user?->position?->echelon?->id ?? 999999;
                            $eb = $b->user?->position?->echelon?->id ?? 999999;
                            if ($ea !== $eb) return $ea <=> $eb; // ASC

                            $ra = $a->user?->rank?->id ?? 0;
                            $rb = $b->user?->rank?->id ?? 0;
                            if ($ra !== $rb) return $rb <=> $ra; // DESC

                            $na = (string)($a->user?->nip ?? '');
                            $nb = (string)($b->user?->nip ?? '');
                            return strcmp($na, $nb);
                        })->values();
                    @endphp
                    @foreach($participants as $i => $p)
                        {{ $i+1 }}. Nama : {{ $p->user?->fullNameWithTitles() }}<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Pangkat/Gol. Ruang : {{ $p->user?->rank?->name ?? '-' }} ({{ $p->user?->rank?->code ?? '-' }})<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;NIP : {{ $p->user?->nip ?? '-' }}<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Jabatan : {{ $p->user?->position_desc ?: ($p->user?->position?->name ?? '-') }}<br>
                        
                        @if(!$loop->last)<br>@endif
                    @endforeach
                </td>
            </tr>
        </table>

        <!-- Untuk -->
        <table class="w-full text-sm mb-4" style="table-layout: fixed;">
            <tr>
                <td style="width: 80px; vertical-align:top;" class="align-top">Untuk</td>
                <td style="width: 20px;" class="align-top">:</td>
                <td class="align-top">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 20px; vertical-align: top;">1.</td>
                            <td style="vertical-align: top;">{{ $spt->notaDinas?->maksud }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">2.</td>
                            <td style="vertical-align: top;">Lamanya perjalanan : {{ $spt->notaDinas?->days_count }} ({{ \Illuminate\Support\Str::of($spt->notaDinas?->days_count)->snake()->replace('_',' ') }}) hari PP dari Tgl. {{ $spt->notaDinas?->start_date ? \Carbon\Carbon::parse($spt->notaDinas->start_date)->locale('id')->translatedFormat('d F Y') : '-' }} s/d {{ $spt->notaDinas?->end_date ? \Carbon\Carbon::parse($spt->notaDinas->end_date)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="width: 20px; vertical-align: top;">3.</td>
                            <td style="vertical-align: top;">Setelah melaksanakan tugas paling lama 5 (Lima) hari menyampaikan laporan tertulis kepada pimpinan</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan -->
        <div class="flex justify-end mt-8">
            <div class="text-left text-sm">
                <br><br>
                <div class="mb-1">Bengkalis, {{ \Carbon\Carbon::parse($spt->spt_date)->locale('id')->translatedFormat('d F Y') }}</div>
                <div class="mb-1">{{ $spt->assignment_title ?: ($spt->signedByUser?->position_desc ?: ($spt->signedByUser?->position?->name ?? '-')) }} {{ \DB::table('org_settings')->value('name') }}</div>
                <div class="mb-1">Kabupaten Bengkalis</div>
                <br><br><br><br>
                <div class="font-bold underline">{{ $spt->signedByUser?->fullNameWithTitles() }}</div>
                <div>{{ $spt->signedByUser?->rank?->name ?? '-' }} ({{ $spt->signedByUser?->rank?->code ?? '-' }})</div>
                <div>NIP. {{ $spt->signedByUser?->nip ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>
