<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stiker QR - {{ $arsip->kode_berkas }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; flex-direction: column; align-items: center; padding: 20px 20px 40px; background-color: #f1f5f9; }
        
        .toolbar { width: 100%; max-width: 320px; margin-bottom: 20px; }
        .toolbar-box { background: #fff; padding: 12px 14px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 11px; text-align: left; box-shadow: 0 2px 4px rgba(0,0,0,0.04); margin-top: 10px; }
        
        /* Desain Stiker Label BPJS */
        .sticker-container { background: white; padding: 20px; border-radius: 12px; text-align: center; border: 2px dashed #059669; width: 260px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); position: relative; }
        
        .header { background-color: #059669; color: white; padding: 8px; border-radius: 6px; font-weight: bold; font-size: 14px; letter-spacing: 1px; margin-bottom: 15px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        .kode { font-size: 22px; font-weight: 900; color: #1f2937; margin-bottom: 5px; }
        .nama-berkas { font-size: 12px; color: #4b5563; margin-bottom: 15px; height: 35px; overflow: hidden; }
        
        .qr-wrapper { display: flex; justify-content: center; margin-bottom: 15px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb; }
        
        .lokasi { font-size: 13px; color: #059669; font-weight: bold; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        .lokasi span { color: #374151; font-weight: normal; font-size: 11px; display: block; margin-top: 3px; }
        
        .print-btn { padding: 10px 20px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; transition: 0.2s; }
        .print-btn:hover { background: #047857; }
        
        @media print {
            body { background-color: white; padding: 0; align-items: flex-start; }
            .toolbar { display: none !important; }
            .sticker-container { border: 1px solid #000; box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="print-btn" onclick="window.print()">Cetak Stiker Label</button>

        @if ($alamatLokal)
            <div class="toolbar-box">
                <p style="margin: 0 0 6px; color: #b45309; font-weight: 700; font-size: 11px;">
                    ⚠️ Kompatibilitas Scan HP:
                </p>
                <p style="margin: 0 0 8px; color: #64748b; line-height: 1.4;">
                    Alamat <code>{{ request()->getHost() }}</code> hanya dikenali komputer ini. Agar bisa di-scan kamera HP:
                </p>
                @if ($pakaiIp)
                    <span style="display: block; background: #059669; color: white; padding: 5px 8px; border-radius: 6px; font-weight: bold; text-align: center; margin-bottom: 6px; font-size: 11px;">
                        ✓ QR memakai IP: {{ $customIp }}
                    </span>
                    <a href="{{ request()->fullUrlWithQuery(['use_ip' => null, 'ip' => null]) }}" style="color: #64748b; font-size: 10px; text-decoration: underline; display: block; text-align: center;">
                        Kembalikan ke Alamat Standar (.test)
                    </a>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['use_ip' => 1]) }}" style="display: block; background: #059669; color: white; padding: 6px 10px; border-radius: 6px; font-weight: bold; text-decoration: none; text-align: center; font-size: 11px;">
                        📱 Ubah QR ke IP Laptop ({{ $lanIp }})
                    </a>
                @endif
            </div>
        @endif
    </div>

    <div class="sticker-container">
        <div class="header">E-ARSIP BPJS</div>
        <div class="kode">{{ $arsip->kode_berkas }}</div>
        <div class="nama-berkas">{{ Str::limit($arsip->nama_berkas, 50) }}</div>
        
        <div class="qr-wrapper">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($url) !!}
        </div>
        
        <div class="lokasi">
            {{ $arsip->ruangan }}
            <span>Rak {{ $arsip->rak }} | Baris {{ $arsip->baris }} | Box {{ $arsip->box }}</span>
        </div>
    </div>
</body>
</html>
