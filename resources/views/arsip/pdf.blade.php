<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Arsip BPJS</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #10b981; color: white; }
        .text-center { text-align: center; }
        .header-title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .header-subtitle { text-align: center; font-size: 14px; margin-bottom: 20px; color: #555; }
    </style>
</head>
<body>
    <div class="header-title">LAPORAN DATA ARSIP FISIK</div>
    <div class="header-subtitle">BPJS Ketenagakerjaan Cabang Rawamangun</div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Berkas</th>
                <th width="35%">Nama Berkas</th>
                <th width="15%">Ruangan</th>
                <th width="10%">Rak</th>
                <th width="10%">Box</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arsips as $index => $arsip)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $arsip->kode_berkas }}</td>
                <td>{{ $arsip->nama_berkas }}</td>
                <td>{{ $arsip->ruangan }}</td>
                <td>{{ $arsip->rak }}</td>
                <td>Box {{ $arsip->box }}</td>
                <td class="text-center">{{ $arsip->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>