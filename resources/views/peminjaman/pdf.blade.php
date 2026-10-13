<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px 10px; text-align: left; }
        th { background-color: #e2e8f0; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 11px; }
        .text-center { text-align: center; }
        .status-dipinjam { color: #d97706; font-weight: bold; }
        .status-kembali { color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekapitulasi Peminjaman Arsip</h2>
        <p>BPJS Ketenagakerjaan Kantor Cabang Rawamangun</p>
        <p style="font-size: 11px; margin-top:3px;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="13%">Kode Berkas</th>
                <th width="18%">Nama Berkas</th>
                <th width="13%">Peminjam</th>
                <th width="12%">Unit/Divisi</th>
                <th width="9%">Tgl Pinjam</th>
                <th width="9%">Tgl Kembali</th>
                <th width="9%">Status</th>
                <th width="14%">Diacc Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $pinjam)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $pinjam->arsip->kode_berkas ?? 'TERHAPUS' }}</strong></td>
                <td>{{ $pinjam->arsip->nama_berkas ?? 'Arsip Telah Dihapus' }}</td>
                <td>{{ $pinjam->nama_peminjam }}</td>
                <td>{{ $pinjam->divisi }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td class="text-center">
                    {{ $pinjam->tanggal_kembali ? \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center">
                    @if($pinjam->status == 'Dipinjam')
                        <span class="status-dipinjam">Dipinjam</span>
                    @else
                        <span class="status-kembali">Kembali</span>
                    @endif
                </td>
                <td class="text-center">{{ $pinjam->disetujui_oleh ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>