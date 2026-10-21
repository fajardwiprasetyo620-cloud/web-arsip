<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $arsips->count() }} Stiker QR - E-Arsip</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #e2e8f0; color: #1f2937; }

        /* Bilah atas (hanya di layar, hilang saat dicetak) */
        .toolbar { position: sticky; top: 0; z-index: 10; background: #fff; border-bottom: 1px solid #cbd5e1; padding: 12px 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        .toolbar h1 { font-size: 16px; margin: 0; }
        .toolbar p { margin: 3px 0 0; font-size: 12px; color: #475569; }
        .toolbar code { background: #f1f5f9; padding: 1px 6px; border-radius: 4px; font-weight: 600; color: #0f766e; }
        .peringatan { margin-top: 8px !important; padding: 8px 10px; border-radius: 6px; background: #fffbeb; border: 1px solid #fcd34d; color: #92400e !important; font-weight: 600; }
        .print-btn { padding: 10px 20px; background: #0f766e; color: #fff; border: 0; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 14px; }
        .print-btn:hover { background: #115e59; }

        /* Lembar A4: 3 kolom label */
        .sheet { width: 210mm; max-width: 100%; margin: 20px auto; padding: 8mm; background: #fff; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); font-size: 0; }
        .label { display: inline-block; vertical-align: top; width: calc(33.333% - 7px); margin: 3px; padding: 8px; border: 1px dashed #6b7280; text-align: center; font-size: 12px; break-inside: avoid; page-break-inside: avoid; }
        .label .header { background: #059669; color: #fff; padding: 3px; border-radius: 4px; font-weight: 700; font-size: 10px; letter-spacing: 1px; margin-bottom: 5px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .label .kode { font-size: 15px; font-weight: 900; line-height: 1.2; word-break: break-all; }
        .label .nama { font-size: 10px; color: #4b5563; line-height: 13px; height: 26px; overflow: hidden; margin: 2px 0 4px; }
        .label .qr { display: inline-block; padding: 4px; background: #fff; border: 1px solid #e5e7eb; border-radius: 4px; line-height: 0; margin-bottom: 4px; }
        .label .lokasi { font-size: 11px; font-weight: 700; color: #059669; border-top: 1px solid #e5e7eb; padding-top: 4px; line-height: 1.3; }
        .label .lokasi span { display: block; font-weight: 400; font-size: 10px; color: #374151; }

        @page { size: A4 portrait; margin: 8mm; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .sheet { margin: 0; padding: 0; width: auto; box-shadow: none; }
        }
    </style>
</head>
<body>
    @php
        $hostSaatIni = request()->getHost();
        $alamatLokal = str_ends_with($hostSaatIni, '.test')
            || in_array($hostSaatIni, ['localhost', '127.0.0.1', '::1'], true);
    @endphp

    <div class="toolbar">
        <div>
            <h1>Cetak QR Code Massal &mdash; {{ $arsips->count() }} label</h1>
            <p>Alamat yang tertanam di QR: <code>{{ url('/') }}/katalog/...</code></p>
            <p>Tips cetak: kertas A4, skala 100%, matikan &quot;Headers and footers&quot;. Satu lembar muat sekitar 12 label.</p>
            @if ($alamatLokal)
                <p class="peringatan">
                    Alamat ini hanya dikenal oleh komputer ini, jadi QR yang dicetak tidak akan terbuka dari HP.
                    Untuk HP, buka halaman ini lewat alamat IP laptop (misalnya http://192.168.x.x:8000), lalu cetak ulang.
                </p>
            @endif
        </div>
        <button class="print-btn" onclick="window.print()">Cetak {{ $arsips->count() }} Stiker</button>
    </div>

    <div class="sheet">
        @foreach ($arsips as $arsip)
            <div class="label">
                <div class="header">E-ARSIP BPJS</div>
                <div class="kode">{{ $arsip->kode_berkas }}</div>
                <div class="nama">{{ Str::limit($arsip->nama_berkas, 60) }}</div>
                <div class="qr">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(route('katalog.show', $arsip->id)) !!}
                </div>
                <div class="lokasi">
                    {{ $arsip->ruangan }}
                    <span>Rak {{ $arsip->rak }} | Baris {{ $arsip->baris }} | Box {{ $arsip->box }}</span>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
