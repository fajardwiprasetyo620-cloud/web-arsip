<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stiker QR - {{ $arsip->kode_berkas }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; padding: 40px; background-color: #e2e8f0; }
        
        /* Desain Stiker Label BPJS */
        .sticker-container { background: white; padding: 20px; border-radius: 12px; text-align: center; border: 2px dashed #059669; width: 260px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); position: relative; }
        
        .header { background-color: #059669; color: white; padding: 8px; border-radius: 6px; font-weight: bold; font-size: 14px; letter-spacing: 1px; margin-bottom: 15px; }
        
        .kode { font-size: 22px; font-weight: 900; color: #1f2937; margin-bottom: 5px; }
        .nama-berkas { font-size: 12px; color: #4b5563; margin-bottom: 15px; height: 35px; overflow: hidden; }
        
        .qr-wrapper { display: flex; justify-content: center; margin-bottom: 15px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; background-color: #f9fafb; }
        
        .lokasi { font-size: 13px; color: #059669; font-weight: bold; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        .lokasi span { color: #374151; font-weight: normal; font-size: 11px; display: block; margin-top: 3px; }
        
        /* Tombol Cetak (Akan hilang saat kertas di-print) */
        .print-btn { margin-top: 30px; padding: 10px 20px; background: #0f766e; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; transition: 0.2s; }
        .print-btn:hover { background: #0f766e; }
        
        @media print {
            body { background-color: white; padding: 0; align-items: flex-start; }
            .print-btn { display: none; }
            .sticker-container { border: 1px solid #000; box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div>
        <div class="sticker-container">
            <div class="header">E-ARSIP BPJS</div>
            <div class="kode">{{ $arsip->kode_berkas }}</div>
            <div class="nama-berkas">{{ Str::limit($arsip->nama_berkas, 50) }}</div>
            
            <div class="qr-wrapper">
                <!-- Memanggil Library QR Code dan menghasilkan gambar SVG -->
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($url) !!}
            </div>
            
            <div class="lokasi">
                {{ $arsip->ruangan }}
                <span>Rak {{ $arsip->rak }} | Box {{ $arsip->box }}</span>
            </div>
        </div>
        
        <button class="print-btn" onclick="window.print()">🖨️ Cetak Stiker Label</button>
    </div>
</body>
</html>