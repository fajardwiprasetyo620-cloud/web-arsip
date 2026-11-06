<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Arsip;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArsipSeeder extends Seeder
{
    /**
     * Jalankan seeder 50 arsip fiktif realistis BPJS Ketenagakerjaan
     * lengkap dengan skenario peminjaman (Tersedia, Dipinjam, Terlambat, Dikembalikan)
     * dan sebaran siklus retensi JRA (Aktif, Inaktif, Jatuh Tempo).
     */
    public function run(): void
    {
        // 1. Matikan sementara pengecekan relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('peminjamans')->truncate();
        DB::table('arsips')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $samplePdf = 'j0401b-00023_WeC357fsAJ5a.pdf';
        $samplePng = 'j0401b-00055_XMYmXyhWw4Tp.png';

        // 2. Daftar 50 Data Arsip Realistis BPJS Ketenagakerjaan
        $dataArsip = [
            // ==========================================
            // RUANG ARSIP KECIL (Rak A s/d D, Baris 1-4)
            // ==========================================

            // JAMINAN HARI TUA (JHT)
            [
                'kode_berkas'    => 'JHT-2026-001',
                'nama_berkas'    => 'Berkas Klaim JHT Usia Pensiun - PT Astra International Tbk (TK: Budi Santoso)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 1,
                'box'            => 'Box 01',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-02-10',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'JHT-2026-002',
                'nama_berkas'    => 'Berkas Klaim JHT Mengundurkan Diri - PT Telekomunikasi Indonesia (TK: Dian Anggraini)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 1,
                'box'            => 'Box 01',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2026-03-14',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2025-015',
                'nama_berkas'    => 'Berkas Klaim JHT PHK Efisiensi - PT Sumber Alfaria Trijaya Tbk (TK: Rahmat Hidayat)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 2,
                'box'            => 'Box 02',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-08-20',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'JHT-2025-022',
                'nama_berkas'    => 'Berkas Klaim JHT Kepesertaan 10 Tahun (Klaim 10%) - PT Indofood CBP (TK: Eko Prasetyo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 2,
                'box'            => 'Box 02',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-11-05',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2024-008',
                'nama_berkas'    => 'Berkas Klaim JHT Meninggalkan RI - PT Huawei Tech Investment (TK: Michael Tan)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 3,
                'box'            => 'Box 03',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2024-05-18',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2024-031',
                'nama_berkas'    => 'Berkas Klaim JHT Cacat Total Tetap - PT Wijaya Karya (Persero) (TK: Hendra Gunawan)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 3,
                'box'            => 'Box 03',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-09-12',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2023-011',
                'nama_berkas'    => 'Berkas Klaim JHT BPU Mandiri - Driver Ojek Online (TK: Supriyadi)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 4,
                'box'            => 'Box 04',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-04-10',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2023-045',
                'nama_berkas'    => 'Berkas Klaim JHT BPU Nelayan - Koperasi Bahari Mandiri (TK: Mansyur)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak A',
                'baris'          => 4,
                'box'            => 'Box 04',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-10-25',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2022-004',
                'nama_berkas'    => 'Berkas Klaim JHT Tenaga Kerja Kontrak (PKWT) - PT SiCepat Ekspres (TK: Fajar Ramadhan)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 1,
                'box'            => 'Box 05',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2022-06-30',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2021-019',
                'nama_berkas'    => 'Berkas Klaim JHT Kolektif Restrukturisasi - PT Sri Rejeki Isman Tbk',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 1,
                'box'            => 'Box 05',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2021-03-15',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2019-002',
                'nama_berkas'    => 'Berkas Klaim JHT Non-Aktif Lawas - PT Hero Supermarket Tbk (TK: Bambang Purnomo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 2,
                'box'            => 'Box 06',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2019-07-12',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JHT-2018-001',
                'nama_berkas'    => 'Berkas Klaim JHT Arsip Statis - Perum Percetakan Negara RI (TK: Hartono)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 2,
                'box'            => 'Box 06',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2018-11-03',
                'file_dokumen'   => null,
            ],

            // JAMINAN KECELAKAAN KERJA (JKK)
            [
                'kode_berkas'    => 'JKK-2026-003',
                'nama_berkas'    => 'Formulir Laporan JKK Tahap I & Kasus Kecelakaan - PT Adhi Karya (TK: Dimas Setiawan)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 3,
                'box'            => 'Box 07',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-01-22',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'JKK-2026-007',
                'nama_berkas'    => 'Berkas Penagihan Biaya Pengobatan PLKK RS Premier - PT Kalbe Farma (TK: Anton Sujarwo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 3,
                'box'            => 'Box 07',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2026-04-05',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2025-012',
                'nama_berkas'    => 'Berkas Penetapan Santunan STMB (Sementara Tidak Mampu Bekerja) - PT United Tractors (TK: Rizky Pratama)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 4,
                'box'            => 'Box 08',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2025-03-10',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2025-028',
                'nama_berkas'    => 'Laporan Kasus Kecelakaan Lalu Lintas Berangkat Kerja - PT Mayora Indah (TK: Nur Cahyo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak B',
                'baris'          => 4,
                'box'            => 'Box 08',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-09-17',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2024-005',
                'nama_berkas'    => 'Berkas Penggantian Alat Bantu Dengar & Orthosa - PT Panasonic Manufacturing (TK: Joko Susilo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 1,
                'box'            => 'Box 09',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-02-14',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2023-018',
                'nama_berkas'    => 'Rekam Medis & Vonis Cacat Sebagian Fungsi Tangan - PT Pindad (TK: Arif Wibowo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 1,
                'box'            => 'Box 09',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-07-28',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2022-010',
                'nama_berkas'    => 'Berkas Program Return to Work (RTW) Pekerja Pabrik - PT Toyota Motor (TK: Bagus Nugroho)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 2,
                'box'            => 'Box 10',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2022-11-19',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKK-2019-003',
                'nama_berkas'    => 'Berkas Kasus Kecelakaan Fatal Meninggal di Lokasi Proyek Tol - PT Hutama Karya',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 2,
                'box'            => 'Box 10',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2019-04-08',
                'file_dokumen'   => null,
            ],

            // JAMINAN KEMATIAN (JKM)
            [
                'kode_berkas'    => 'JKM-2026-002',
                'nama_berkas'    => 'Berkas Klaim Santunan Kematian & Beasiswa Anak - Alm. Slamet Riyadi (PT Samudera Indonesia)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 3,
                'box'            => 'Box 11',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-01-18',
                'file_dokumen'   => $samplePng,
            ],
            [
                'kode_berkas'    => 'JKM-2025-009',
                'nama_berkas'    => 'Berkas Penetapan Ahli Waris Manfaat Santunan Berkala - Almh. Dewi Kartika (PT Kimia Farma)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 3,
                'box'            => 'Box 11',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2025-06-22',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKM-2024-014',
                'nama_berkas'    => 'Surat Keterangan Kematian & Biaya Pemakaman - Alm. Agus Salim (Pekerja Rentan Mandiri)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 4,
                'box'            => 'Box 12',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-08-09',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKM-2023-021',
                'nama_berkas'    => 'Berkas Verifikasi Keabsahan Surat Nikah Ahli Waris JKM - Alm. M. Thohir (PT Pelindo)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak C',
                'baris'          => 4,
                'box'            => 'Box 12',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-03-30',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKM-2021-007',
                'nama_berkas'    => 'Berkas Santunan JKM Pandemi Pekerja Medis - Almh. dr. Ratna Juwita (RSUD Cempaka)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 1,
                'box'            => 'Box 13',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2021-08-14',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKM-2019-005',
                'nama_berkas'    => 'Berkas Klaim Kematian Bukan Akibat Kecelakaan Kerja - Alm. Danang Sutrisno (CV Surya)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 1,
                'box'            => 'Box 13',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2019-12-01',
                'file_dokumen'   => null,
            ],

            // JAMINAN PENSIUN (JP)
            [
                'kode_berkas'    => 'JP-2026-001',
                'nama_berkas'    => 'Penetapan Manfaat JP Pensiun Hari Tua Berkala - PT Bank Central Asia Tbk (TK: Soekirno)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 2,
                'box'            => 'Box 14',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2026-02-01',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'JP-2025-004',
                'nama_berkas'    => 'Formulir Pendaftaran Rekening JP Berkala Bank BTN - PT Pos Indonesia (TK: Suyatno)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 2,
                'box'            => 'Box 14',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-04-12',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JP-2024-010',
                'nama_berkas'    => 'Berkas Manfaat JP Janda/Duda Konfirmasi Anak - PT Pertamina (Persero) (TK: Wardhana)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 3,
                'box'            => 'Box 15',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-10-15',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JP-2023-006',
                'nama_berkas'    => 'Berkas Klaim JP Lump Sum Iuran Kurang 15 Tahun - PT Garudafood Putra Putri (TK: Rusli)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 3,
                'box'            => 'Box 15',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-05-20',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JP-2022-013',
                'nama_berkas'    => 'Surat Pernyataan Ahli Waris Penerima JP Anak Yatim - PT Timah Tbk (TK: Syamsuddin)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 4,
                'box'            => 'Box 16',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2022-02-17',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JP-2020-002',
                'nama_berkas'    => 'Berkas Penetapan JP Pertama Kali Berlakunya UU BPJS - PT PLN (Persero) (TK: Wahyudi)',
                'ruangan'        => 'Ruang Arsip Kecil',
                'rak'            => 'Rak D',
                'baris'          => 4,
                'box'            => 'Box 16',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2020-06-25',
                'file_dokumen'   => null,
            ],

            // ===============================================
            // RUANG ARSIP KEPESERTAAN (Rak A s/d D, Baris 1-6)
            // ===============================================

            // JAMINAN KEHILANGAN PEKERJAAN (JKP)
            [
                'kode_berkas'    => 'JKP-2026-004',
                'nama_berkas'    => 'Berkas Manfaat Uang Tunai Bulan ke-1 JKP - Eks Karyawan PT Tokopedia (TK: Kevin Sanjaya)',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak A',
                'baris'          => 1,
                'box'            => 'Box 20',
                'status'         => 'Dipinjam',
                'tanggal_berkas' => '2026-03-01',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKP-2025-011',
                'nama_berkas'    => 'Bukti Lapor Lolos Asesmen Diri SiapKerja Kemnaker - PT Shopee International (TK: Nita Sari)',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak A',
                'baris'          => 2,
                'box'            => 'Box 20',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-07-19',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'JKP-2024-009',
                'nama_berkas'    => 'Berkas Bukti PHK Penetapan Pengadilan PHI - PT Bukalapak.com Tbk (TK: Derry Hermawan)',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak A',
                'baris'          => 3,
                'box'            => 'Box 21',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-09-02',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'JKP-2023-003',
                'nama_berkas'    => 'Berkas Rekomendasi Konseling Karir Pelatihan Vokasi JKP - CV Media Graha (TK: Indah Wulandari)',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak A',
                'baris'          => 4,
                'box'            => 'Box 21',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-11-28',
                'file_dokumen'   => null,
            ],

            // KEPESERTAAN & PEMBINAAN BADAN USAHA (KPS)
            [
                'kode_berkas'    => 'KPS-2026-010',
                'nama_berkas'    => 'Berkas Pendaftaran Badan Usaha Baru (Form 1/1a) - PT Digital Solusi Cemerlang',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 1,
                'box'            => 'Box 22',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-02-28',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2026-018',
                'nama_berkas'    => 'Formulir Pendaftaran Perluasan 5 Program - Yayasan Pendidikan Bina Nusantara',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 2,
                'box'            => 'Box 22',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-04-10',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2025-007',
                'nama_berkas'    => 'Berkas Perubahan Data Nama Tenaga Kerja & Upah (Form 2a) - PT Sinarmas Multiartha Tbk',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 3,
                'box'            => 'Box 23',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-05-14',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2025-030',
                'nama_berkas'    => 'Laporan Mutasi Penonaktifan Massal Proyek Konstruksi - PT Total Bangun Persada',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 4,
                'box'            => 'Box 23',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-10-30',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2024-012',
                'nama_berkas'    => 'Berkas Verifikasi Kepesertaan BPU Komunitas Pengemudi Taksi Bandara',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 5,
                'box'            => 'Box 24',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-01-25',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2024-025',
                'nama_berkas'    => 'PKS Perlindungan Pekerja Rentan APBD Suku Dinas Tenaga Kerja Jakarta Timur',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak B',
                'baris'          => 6,
                'box'            => 'Box 24',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-07-14',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2023-014',
                'nama_berkas'    => 'Berkas Surat Peringatan Pertama (SP 1) Piutang Iuran - PT Trimitra Sentosa',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 1,
                'box'            => 'Box 25',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2023-08-11',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KPS-2020-001',
                'nama_berkas'    => 'Arsip Berkas Berita Acara Pemeriksaan Petugas Wasrik - PT Citra Abadi Perkasa',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 2,
                'box'            => 'Box 25',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2020-10-05',
                'file_dokumen'   => null,
            ],

            // KEUANGAN & ANGGARAN (KEU)
            [
                'kode_berkas'    => 'KEU-2026-005',
                'nama_berkas'    => 'Berkas Rekonsiliasi Iuran Penerimaan Bank Persepsi Mandiri - Periode Januari 2026',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 3,
                'box'            => 'Box 26',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-02-05',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KEU-2025-018',
                'nama_berkas'    => 'SPJ Biaya Sosialisasi & Promosi Program BPU Pasar Rawamangun',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 4,
                'box'            => 'Box 26',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2025-09-15',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KEU-2024-011',
                'nama_berkas'    => 'Berkas Pengembalian Kelebihan Bayar Iuran (Refund) - PT Gajah Tunggal Tbk',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 5,
                'box'            => 'Box 27',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-04-18',
                'file_dokumen'   => null,
            ],
            [
                'kode_berkas'    => 'KEU-2022-008',
                'nama_berkas'    => 'Berkas Pemeriksaan Laporan Keuangan Tahunan oleh Kantor Akuntan Publik (KAP)',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak C',
                'baris'          => 6,
                'box'            => 'Box 27',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2022-12-20',
                'file_dokumen'   => null,
            ],

            // UMUM, SDM & KELENGKAPAN CABANG (UMM)
            [
                'kode_berkas'    => 'UMM-2026-002',
                'nama_berkas'    => 'Perjanjian Kerjasama (PKS) PLKK Rumah Sakit Persahabatan Rawamangun',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak D',
                'baris'          => 1,
                'box'            => 'Box 28',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2026-01-10',
                'file_dokumen'   => $samplePdf,
            ],
            [
                'kode_berkas'    => 'UMM-2024-007',
                'nama_berkas'    => 'Surat Keputusan (SK) Pengangkatan & Mutasi Pegawai Kantor Cabang Rawamangun',
                'ruangan'        => 'Ruang Arsip Kepesertaan',
                'rak'            => 'Rak D',
                'baris'          => 2,
                'box'            => 'Box 28',
                'status'         => 'Tersedia',
                'tanggal_berkas' => '2024-06-01',
                'file_dokumen'   => null,
            ],
        ];

        // 3. Masukkan 50 Arsip ke Database
        $now = Carbon::now();
        $arsipIdsByKode = [];

        foreach ($dataArsip as $item) {
            $created = Arsip::create([
                'kode_berkas'    => $item['kode_berkas'],
                'nama_berkas'    => $item['nama_berkas'],
                'ruangan'        => $item['ruangan'],
                'rak'            => $item['rak'],
                'baris'          => $item['baris'],
                'box'            => $item['box'],
                'status'         => $item['status'],
                'tanggal_berkas' => $item['tanggal_berkas'],
                'file_dokumen'   => $item['file_dokumen'],
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $arsipIdsByKode[$item['kode_berkas']] = $created->id;
        }

        // 4. Masukkan Skenario Peminjaman untuk Demo (10 Peminjaman)
        // 4a. 4 Berkas AKTIF DIPINJAM (Belum Jatuh Tempo)
        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JHT-2026-002'],
            'nama_peminjam'  => 'Siti Nurhaliza',
            'divisi'         => 'Bidang Pelayanan & Klaim',
            'tanggal_pinjam' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->addDays(5)->format('Y-m-d'),
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JKK-2026-007'],
            'nama_peminjam'  => 'dr. Maya Indraswari',
            'divisi'         => 'Bidang Pelayanan & Klaim',
            'tanggal_pinjam' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->addDays(4)->format('Y-m-d'),
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JP-2026-001'],
            'nama_peminjam'  => 'Bambang Irawan',
            'divisi'         => 'Bidang Keuangan & IT',
            'tanggal_pinjam' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->addDays(6)->format('Y-m-d'),
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JKP-2026-004'],
            'nama_peminjam'  => 'Ilham Pratama',
            'divisi'         => 'Bidang Kepesertaan',
            'tanggal_pinjam' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->addDays(5)->format('Y-m-d'),
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        // 4b. 3 Berkas TERLAMBAT (Lewat Jatuh Tempo) -> untuk demo Banner Merah & Broadcast WA
        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JHT-2024-008'],
            'nama_peminjam'  => 'Dian Anggraini',
            'divisi'         => 'Bidang Pelayanan & Klaim',
            'tanggal_pinjam' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->subDays(12)->format('Y-m-d'), // Terlambat 12 hari
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JKK-2025-012'],
            'nama_peminjam'  => 'Rizky Pratama',
            'divisi'         => 'Bidang Pemasaran',
            'tanggal_pinjam' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->subDays(7)->format('Y-m-d'), // Terlambat 7 hari
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'       => $arsipIdsByKode['JKM-2025-009'],
            'nama_peminjam'  => 'Agus Setiawan',
            'divisi'         => 'Bidang Umum & SDM',
            'tanggal_pinjam' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'tenggat_waktu'  => Carbon::now()->subDays(3)->format('Y-m-d'), // Terlambat 3 hari
            'status'         => 'Dipinjam',
            'disetujui_oleh' => 'Fajar Dwi Prasetyo',
        ]);

        // 4c. 3 Berkas SUDAH DIKEMBALIKAN (Riwayat Transaksi Lengkap)
        Peminjaman::create([
            'arsip_id'        => $arsipIdsByKode['JHT-2026-001'],
            'nama_peminjam'   => 'Hendra Kusuma',
            'divisi'          => 'Bidang Pelayanan & Klaim',
            'tanggal_pinjam'  => Carbon::now()->subDays(14)->format('Y-m-d'),
            'tenggat_waktu'   => Carbon::now()->subDays(7)->format('Y-m-d'),
            'tanggal_kembali' => Carbon::now()->subDays(8)->format('Y-m-d'),
            'status'          => 'Dikembalikan',
            'disetujui_oleh'  => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'        => $arsipIdsByKode['KPS-2026-010'],
            'nama_peminjam'   => 'Nurul Hidayah',
            'divisi'          => 'Bidang Kepesertaan',
            'tanggal_pinjam'  => Carbon::now()->subDays(12)->format('Y-m-d'),
            'tenggat_waktu'   => Carbon::now()->subDays(5)->format('Y-m-d'),
            'tanggal_kembali' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'status'          => 'Dikembalikan',
            'disetujui_oleh'  => 'Fajar Dwi Prasetyo',
        ]);

        Peminjaman::create([
            'arsip_id'        => $arsipIdsByKode['UMM-2026-002'],
            'nama_peminjam'   => 'Tri Wahyuni',
            'divisi'          => 'Bidang Umum & SDM',
            'tanggal_pinjam'  => Carbon::now()->subDays(8)->format('Y-m-d'),
            'tenggat_waktu'   => Carbon::now()->subDays(1)->format('Y-m-d'),
            'tanggal_kembali' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'status'          => 'Dikembalikan',
            'disetujui_oleh'  => 'Fajar Dwi Prasetyo',
        ]);

        // 5. Catat ke Activity Log
        ActivityLog::catat(
            'TAMBAH',
            'menghasilkan 50 arsip fiktif realistis BPJS Ketenagakerjaan (JHT, JKK, JKM, JP, JKP, KPS, KEU, UMM) untuk keperluan demo simulasi sistem.'
        );
    }
}
