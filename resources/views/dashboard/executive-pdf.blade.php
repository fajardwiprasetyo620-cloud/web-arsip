<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Eksekutif Kearsipan - BPJS Ketenagakerjaan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm 1.5cm 1.2cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.35;
        }
        .header {
            border-bottom: 2.5px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header table {
            width: 100%;
        }
        .header h1 {
            font-size: 15pt;
            color: #059669;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 10pt;
            color: #334155;
            margin: 3px 0 0 0;
            font-weight: normal;
        }
        .header p {
            font-size: 8pt;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .report-meta {
            text-align: right;
            font-size: 8pt;
            color: #64748b;
        }

        /* Metric KPI Cards */
        .kpi-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }
        .kpi-title {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .kpi-val {
            font-size: 16pt;
            font-weight: bold;
            color: #059669;
        }
        .kpi-sub {
            font-size: 7.5pt;
            color: #475569;
            margin-top: 2px;
        }

        /* Tables */
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 12px 0 6px 0;
            border-left: 3px solid #059669;
            padding-left: 6px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger  { background-color: #ffe4e6; color: #9f1239; }

        /* Signatures */
        .signatures {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 9pt;
        }
        .sig-space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 70%;">
                    <h1>BPJS Ketenagakerjaan</h1>
                    <h2>Kantor Cabang Jakarta Rawamangun &mdash; Unit Tata Kelola Kearsipan</h2>
                    <p>Jl. Balai Pustaka No. 43, Rawamangun, Jakarta Timur | Telp: (021) 4786-9000</p>
                </td>
                <td class="report-meta">
                    <strong>LAPORAN EKSEKUTIF KEARSIPAN</strong><br>
                    Dicetak pada: {{ $tanggalCetak }} WIB<br>
                    Dokumen Resmi Sistem E-Arsip
                </td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan KPI -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Berkas Fisik</div>
                <div class="kpi-val">{{ number_format($totalArsip, 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $arsipTersedia }} Tersedia &middot; {{ $arsipDipinjam }} Dipinjam</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Rasio Digitalisasi (Scan)</div>
                <div class="kpi-val">{{ $persenDigital }}%</div>
                <div class="kpi-sub">{{ $arsipDigital }} Berkas Softcopy PDF</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Peminjaman Melewati Tenggat</div>
                <div class="kpi-val" style="color: {{ $jumlahTerlambat > 0 ? '#e11d48' : '#059669' }};">{{ $jumlahTerlambat }}</div>
                <div class="kpi-sub">{{ $jumlahTerlambat > 0 ? 'Perlu tindakan penagihan segera' : 'Tertib tepat waktu' }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Daya Tampung Rak Fisik</div>
                <div class="kpi-val" style="color: #0284c7;">Optimal</div>
                <div class="kpi-sub">Tersebar di 2 Ruang Penyimpanan</div>
            </td>
        </tr>
    </table>

    <!-- Dua Kolom: Kapasitas Ruang & Distribusi Peminjaman -->
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Kolom Kiri: Kapasitas Ruang & Rak -->
            <td style="width: 58%; vertical-align: top; padding-right: 12px;">
                <div class="section-title">Rekapitulasi Daya Tampung Ruang & Lemari Rak</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ruangan</th>
                            <th>Rak</th>
                            <th style="text-align: center;">Kapasitas Baris</th>
                            <th style="text-align: center;">Total Box</th>
                            <th style="text-align: center;">Isi Berkas</th>
                            <th style="text-align: center;">Keterisian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kapasitasRuang as $ruangItem)
                            @foreach($ruangItem['raks'] as $idx => $rak)
                                <tr>
                                    @if($idx === 0)
                                        <td rowspan="{{ count($ruangItem['raks']) }}" style="font-weight: bold; background-color: #fafafa;">
                                            {{ $ruangItem['ruangan'] }}
                                            <div style="font-size: 7.5pt; font-weight: normal; color: #64748b;">Total: {{ $ruangItem['total_arsip'] }} berkas</div>
                                        </td>
                                    @endif
                                    <td style="font-weight: 600;">{{ $rak['nama'] }}</td>
                                    <td style="text-align: center;">{{ $rak['baris_terisi'] }} / {{ $rak['max_baris'] }} baris</td>
                                    <td style="text-align: center;">{{ $rak['total_box'] }} box</td>
                                    <td style="text-align: center; font-weight: bold;">{{ $rak['total_arsip'] }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge {{ $rak['persen'] >= 85 ? 'badge-danger' : ($rak['persen'] >= 50 ? 'badge-warning' : 'badge-success') }}">
                                            {{ $rak['persen'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </td>

            <!-- Kolom Kanan: Peminjaman per Divisi & Catatan Overdue -->
            <td style="width: 42%; vertical-align: top; padding-left: 12px;">
                <div class="section-title">Distribusi Peminjaman per Unit Kerja / Divisi</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Unit Kerja / Divisi</th>
                            <th style="text-align: center;">Volume Pinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanPerDivisi as $divisiItem)
                            <tr>
                                <td>{{ $divisiItem->divisi }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $divisiItem->total }} berkas</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #94a3b8;">Belum ada data peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($jumlahTerlambat > 0)
                    <div class="section-title" style="border-left-color: #e11d48; color: #9f1239;">Daftar Peminjaman Terlambat (Prioritas Follow-up)</div>
                    <table class="data-table">
                        <thead>
                            <tr style="background-color: #ffe4e6;">
                                <th>Peminjam</th>
                                <th>Berkas</th>
                                <th style="text-align: center;">Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($terlambatList->take(4) as $overdue)
                                <tr>
                                    <td>{{ $overdue->nama_peminjam }} <span style="font-size: 7.5pt; color: #64748b;">({{ $overdue->divisi }})</span></td>
                                    <td style="font-weight: 600;">{{ $overdue->arsip->kode_berkas ?? '-' }}</td>
                                    <td style="text-align: center; color: #e11d48; font-weight: bold;">
                                        {{ \Carbon\Carbon::parse($overdue->tenggat_waktu)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala Kantor Cabang Rawamangun</strong>
                <div class="sig-space"></div>
                <strong><u>( Deny Suwardani )</u></strong><br>
                NPP. 98012431
            </td>
            <td>
                Jakarta, {{ now()->translatedFormat('d F Y') }}<br>
                <strong>Penanggung Jawab Kearsipan & IT</strong>
                <div class="sig-space"></div>
                <strong><u>( Fajar Dwi Prasetyo )</u></strong><br>
                NPP. Intern-2026
            </td>
        </tr>
    </table>

</body>
</html>

