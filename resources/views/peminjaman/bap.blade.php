<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Peminjaman Arsip - {{ $nomorBap }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #1a202c;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #047857;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header .instansi {
            font-size: 14pt;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .header .cabang {
            font-size: 11pt;
            font-weight: bold;
            color: #1f2937;
            margin: 3px 0 0 0;
            text-transform: uppercase;
        }
        .header .alamat {
            font-size: 8.5pt;
            color: #4b5563;
            margin: 3px 0 0 0;
        }
        .judul-bap {
            text-align: center;
            margin-bottom: 22px;
        }
        .judul-bap h3 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .judul-bap .nomor {
            font-size: 10pt;
            margin-top: 4px;
            color: #374151;
            font-weight: normal;
        }
        .identitas-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .identitas-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .identitas-table td.label {
            width: 25%;
            font-weight: bold;
            color: #374151;
        }
        .identitas-table td.separator {
            width: 3%;
            text-align: center;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 18px;
            font-size: 9.5pt;
        }
        .table-data th, .table-data td {
            border: 1px solid #94a3b8;
            padding: 7px 8px;
        }
        .table-data th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .klausul {
            font-size: 9pt;
            color: #374151;
            margin-bottom: 25px;
            border-left: 3px solid #047857;
            padding-left: 10px;
            background-color: #f8fafc;
            padding: 8px 10px;
        }
        .klausul p {
            margin: 0 0 4px 0;
            font-weight: bold;
        }
        .klausul ol {
            margin: 0;
            padding-left: 18px;
        }
        .klausul li {
            margin-bottom: 2px;
        }
        .ttd-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .ttd-table td {
            text-align: center;
            vertical-align: top;
            width: 33.33%;
        }
        .ttd-space {
            height: 65px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-jabatan {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- KOP RESMI BPJS KETENAGAKERJAAN -->
    <div class="header">
        <h1 class="instansi">BPJS Ketenagakerjaan</h1>
        <h2 class="cabang">Kantor Cabang Jakarta Rawamangun</h2>
        <p class="alamat">Unit Pengelola Kearsipan & Dokumentasi Internal &middot; Jl. Pemuda No. 70, Rawamangun, Pulo Gadung, Jakarta Timur</p>
    </div>

    <!-- JUDUL DAN NOMOR SURAT -->
    <div class="judul-bap">
        <h3>Berita Acara Serah Terima Peminjaman Arsip</h3>
        <div class="nomor">Nomor: {{ $nomorBap }}</div>
    </div>

    <p style="margin-bottom: 12px; font-size: 10pt;">
        Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::parse($first->tanggal_pinjam)->translatedFormat('d F Y') }}</strong>, telah dilakukan peminjaman dokumen arsip fisik dengan rincian penerima sebagai berikut:
    </p>

    <!-- DATA PEMINJAM -->
    <table class="identitas-table">
        <tr>
            <td class="label">Nama Peminjam</td>
            <td class="separator">:</td>
            <td><strong>{{ $first->nama_peminjam }}</strong></td>
        </tr>
        <tr>
            <td class="label">Unit Kerja / Divisi</td>
            <td class="separator">:</td>
            <td>{{ $first->divisi }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pinjam</td>
            <td class="separator">:</td>
            <td>{{ \Carbon\Carbon::parse($first->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Batas Waktu Pengembalian</td>
            <td class="separator">:</td>
            <td>
                @if($first->tenggat_waktu)
                    <strong>{{ \Carbon\Carbon::parse($first->tenggat_waktu)->translatedFormat('d F Y') }}</strong>
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <p style="margin-bottom: 6px; font-weight: bold; font-size: 10pt;">Daftar Dokumen Arsip yang Dipinjam:</p>

    <!-- TABEL DAFTAR ARSIP -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Kode Berkas</th>
                <th style="width: 45%;">Nama Dokumen Berkas</th>
                <th style="width: 25%;">Lokasi Fisik Ruang / Rak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $idx => $pinjam)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold; color: #047857;">{{ $pinjam->arsip->kode_berkas ?? '-' }}</td>
                    <td>{{ $pinjam->arsip->nama_berkas ?? '-' }}</td>
                    <td style="font-size: 8.5pt;">
                        @if($pinjam->arsip)
                            {{ $pinjam->arsip->ruangan }}<br>
                            <strong>{{ $pinjam->arsip->rak }} &middot; Baris {{ $pinjam->arsip->baris }} &middot; Box {{ $pinjam->arsip->box }}</strong>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- KLAUSUL TANGGUNG JAWAB -->
    <div class="klausul">
        <p>Ketentuan Peminjaman:</p>
        <ol>
            <li>Peminjam bertanggung jawab penuh atas keutuhan, kerahasiaan, dan keamanan dokumen fisik.</li>
            <li>Dokumen dilarang digandakan atau dipindahtangankan kepada pihak lain tanpa izin Petugas Arsip.</li>
            <li>Dokumen wajib dikembalikan ke ruang arsip sebelum atau pada batas waktu pengembalian yang ditetapkan.</li>
        </ol>
    </div>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                <div>Yang Menerima,</div>
                <div style="font-size: 8.5pt; color: #64748b;">(Peminjam Berkas)</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">{{ $first->nama_peminjam }}</div>
                <div class="ttd-jabatan">{{ $first->divisi }}</div>
            </td>
            <td>
                <div>Yang Menyerahkan,</div>
                <div style="font-size: 8.5pt; color: #64748b;">(Petugas Pengelola Arsip)</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">{{ auth()->user()->name ?? 'Petugas Arsip' }}</div>
                <div class="ttd-jabatan">Tim IT & Kearsipan</div>
            </td>
            <td>
                <div>Mengetahui,</div>
                <div style="font-size: 8.5pt; color: #64748b;">(Kepala Bidang / Pjs)</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">( .................................................. )</div>
                <div class="ttd-jabatan">NPP. .....................................</div>
            </td>
        </tr>
    </table>

</body>
</html>

