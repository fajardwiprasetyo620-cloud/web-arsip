<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Services\DatabaseBackupService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ResetAndSeedArsipCommand extends Command
{
    protected $signature = 'arsip:reset-seed {--force : Lewati konfirmasi dan jalankan langsung}';

    protected $description = 'Reset seluruh data arsip & peminjaman, lalu isi dengan 50 data arsip BPJS Ketenagakerjaan fiktif yang realistis.';

    public function handle(DatabaseBackupService $backupService): int
    {
        // 1. Proteksi keamanan: cegah eksekusi di lingkungan production tanpa flag --force
        if (app()->isProduction() && ! $this->option('force')) {
            $this->error('PERINGATAN KEAMANAN: Perintah ini dicegah berjalan di lingkungan PRODUCTION demi integritas data.');
            $this->error('Gunakan opsi --force jika Anda memiliki otorisasi penuh untuk mereset basis data produksi.');

            return 1;
        }

        // 2. Konfirmasi interaktif untuk mencegah salah jalan secara tidak sengaja
        if (! $this->option('force')) {
            if (! $this->confirm('PERINGATAN: Perintah ini akan MENGHAPUS SELURUH data arsip, peminjaman, dan log aktivitas. Apakah Anda yakin ingin melanjutkan?')) {
                $this->info('Operasi reset dibatalkan. Data Anda tetap aman.');

                return 0;
            }
        }

        $this->warn('Memulai proses reset data arsip, peminjaman, dan log...');

        try {
            $backupPath = $backupService->backup();
        } catch (\Throwable $e) {
            $this->error('Reset dibatalkan karena backup sebelum reset gagal: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->info("Backup sebelum reset berhasil dibuat: {$backupPath}");

        // 1. Bersihkan tabel terkait tanpa menghapus user admin
        Schema::disableForeignKeyConstraints();
        try {
            Peminjaman::truncate();
            Arsip::withTrashed()->forceDelete();
            ActivityLog::truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->info('Tabel arsips, peminjamans, dan activity_logs berhasil dikosongkan.');

        // 2. Daftar 50 arsip realistis BPJS Ketenagakerjaan
        $dataBerkas = [
            // 25 Berkas di Ruang Arsip Kecil (baris 1 - 4)
            [
                'kode_berkas' => 'J0401B-00001',
                'nama_berkas' => 'Berkas Klaim JHT - PT Astra International Tbk (TK: Budi Santoso)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 1, 'box' => 'Box 01',
                'tanggal_berkas' => '2023-01-15', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00002',
                'nama_berkas' => 'Berkas Klaim JKK - PT Wijaya Karya (Persero) (TK: Ahmad Hidayat)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 1, 'box' => 'Box 01',
                'tanggal_berkas' => '2023-02-20', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00003',
                'nama_berkas' => 'Berkas Klaim JKM - PT Indofood Sukses Makmur (Ahli Waris: Siti Aminah)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 2, 'box' => 'Box 01',
                'tanggal_berkas' => '2023-03-10', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00004',
                'nama_berkas' => 'Berkas Klaim JP Berkala - PT Telkom Indonesia (TK: Bambang Purnomo)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 2, 'box' => 'Box 02',
                'tanggal_berkas' => '2023-04-18', 'status' => 'Dipinjam', // akan dibuat peminjaman aktif
            ],
            [
                'kode_berkas' => 'J0401B-00005',
                'nama_berkas' => 'Berkas Klaim JKP - PT Matahari Department Store (TK: Rina Marlina)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 3, 'box' => 'Box 02',
                'tanggal_berkas' => '2023-05-25', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00006',
                'nama_berkas' => 'Formulir Pendaftaran Kepesertaan Baru (F1) - PT GoTo Gojek Tokopedia',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 3, 'box' => 'Box 02',
                'tanggal_berkas' => '2023-06-12', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00007',
                'nama_berkas' => 'Berkas Mutasi Data Tenaga Kerja (F1A) - PT Bank Central Asia Tbk',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 4, 'box' => 'Box 03',
                'tanggal_berkas' => '2023-07-08', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00008',
                'nama_berkas' => 'Laporan Rincian Iuran (F2) Bulan Januari 2026 - PT Unilever Indonesia Tbk',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 4, 'box' => 'Box 03',
                'tanggal_berkas' => '2026-01-20', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00009',
                'nama_berkas' => 'Surat Perjanjian Kerjasama PLKK - RS Premier Jatinegara',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 1, 'box' => 'Box 03',
                'tanggal_berkas' => '2023-08-14', 'status' => 'Dipinjam', // akan dibuat peminjaman terlambat
            ],
            [
                'kode_berkas' => 'J0401B-00010',
                'nama_berkas' => 'Berkas Klaim JHT Usia Pensiun 56 Th - PT Kimia Farma (TK: Hendra Gunawan)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 1, 'box' => 'Box 03',
                'tanggal_berkas' => '2023-09-05', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00011',
                'nama_berkas' => 'Berkas Klaim JKK Kasus Lalu Lintas - PT Transportasi Jakarta (TK: Dedi S.)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 2, 'box' => 'Box 04',
                'tanggal_berkas' => '2023-10-11', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00012',
                'nama_berkas' => 'Berkas Klaim JKM Penyakit Kerja - RS Persahabatan (Ahli Waris: Sri Wahyuni)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 2, 'box' => 'Box 04',
                'tanggal_berkas' => '2023-11-23', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00013',
                'nama_berkas' => 'Berkas Klaim JP Manfaat Sekaligus - PT Kereta Api Indonesia (TK: Joko Susilo)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 3, 'box' => 'Box 04',
                'tanggal_berkas' => '2023-12-05', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00014',
                'nama_berkas' => 'Berkas Kepesertaan Jasa Konstruksi - Proyek Renovasi Terminal Rawamangun',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 3, 'box' => 'Box 04',
                'tanggal_berkas' => '2024-01-16', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00015',
                'nama_berkas' => 'Berkas Kepesertaan BPU - Paguyuban Pedagang Pasar Rawamangun',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 4, 'box' => 'Box 05',
                'tanggal_berkas' => '2024-02-28', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00016',
                'nama_berkas' => 'Berkas Klaim JHT Mengundurkan Diri - PT Shopee International Indonesia',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 1, 'box' => 'Box 05',
                'tanggal_berkas' => '2024-03-14', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00017',
                'nama_berkas' => 'Berkas Klaim JKK Biaya PLKK - RS Columbia Asia Pulomas',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 1, 'box' => 'Box 05',
                'tanggal_berkas' => '2024-04-09', 'status' => 'Dipinjam', // akan dibuat peminjaman aktif
            ],
            [
                'kode_berkas' => 'J0401B-00018',
                'nama_berkas' => 'Berkas Pengajuan Beasiswa Anak Ahli Waris JKK/JKM - Ananda Muhammad Rizky',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 2, 'box' => 'Box 05',
                'tanggal_berkas' => '2024-05-18', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00019',
                'nama_berkas' => 'Berkas Rekonsiliasi Iuran BPU Perbankan - Bank DKI Cabang Rawamangun',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 2, 'box' => 'Box 06',
                'tanggal_berkas' => '2024-06-22', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00020',
                'nama_berkas' => 'Berkas Pemeriksaan Kepatuhan Iuran (Wasrik) - CV Cahaya Baru Abadi',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 3, 'box' => 'Box 06',
                'tanggal_berkas' => '2024-07-15', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00021',
                'nama_berkas' => 'Berkas Klaim JHT Non-Aktif > 1 Bulan - PT Garuda Indonesia Tbk',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 3, 'box' => 'Box 06',
                'tanggal_berkas' => '2024-08-30', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00022',
                'nama_berkas' => 'Berkas Klaim JKK Cacat Sebagian Anatomis - PT Krakatau Steel (TK: Agus P.)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 4, 'box' => 'Box 06',
                'tanggal_berkas' => '2024-09-17', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00023',
                'nama_berkas' => 'Berkas Pendaftaran Pekerja Migran Indonesia (PMI) - Wilayah Jakarta Timur',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 1, 'box' => 'Box 07',
                'tanggal_berkas' => '2024-10-04', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00024',
                'nama_berkas' => 'Berkas Klaim JKM Kecelakaan Kerja - PT Adhi Karya (Ahli Waris: Euis K.)',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 2, 'box' => 'Box 07',
                'tanggal_berkas' => '2024-11-12', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00025',
                'nama_berkas' => 'Perjanjian Pembayaran Bertahap Tunggakan Iuran - PT Mitra Selaras Mandiri',
                'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 3, 'box' => 'Box 07',
                'tanggal_berkas' => '2024-12-20', 'status' => 'Tersedia',
            ],

            // 25 Berkas di Ruang Arsip Kepesertaan (baris 1 - 6)
            [
                'kode_berkas' => 'J0401B-00026',
                'nama_berkas' => 'Berkas Klaim JHT Tenaga Kerja Asing - PT Toyota Motor Manufacturing',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 1, 'box' => 'Box 01',
                'tanggal_berkas' => '2025-01-10', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00027',
                'nama_berkas' => 'Verifikasi Dokumen Klaim JHT Online (Lapor Asik) - Batch 12/2025',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 2, 'box' => 'Box 01',
                'tanggal_berkas' => '2025-02-14', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00028',
                'nama_berkas' => 'Berkas Klaim JP Manfaat Janda/Duda Pensiun - Ny. Kusmiyati (Alm. Wardoyo)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 3, 'box' => 'Box 02',
                'tanggal_berkas' => '2025-03-01', 'status' => 'Dipinjam', // akan dibuat peminjaman terlambat
            ],
            [
                'kode_berkas' => 'J0401B-00029',
                'nama_berkas' => 'Pendaftaran Badan Usaha Baru Sektor Kuliner - PT Kopi Kenangan Sejahtera',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 4, 'box' => 'Box 02',
                'tanggal_berkas' => '2025-03-22', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00030',
                'nama_berkas' => 'Berkas Klaim JKK Santunan STMB - PT Pos Indonesia (Persero)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 5, 'box' => 'Box 02',
                'tanggal_berkas' => '2025-04-17', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00031',
                'nama_berkas' => 'Berkas Klaim JHT PHK Efisiensi - PT Dan Liris Indonesia',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 6, 'box' => 'Box 03',
                'tanggal_berkas' => '2025-05-09', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00032',
                'nama_berkas' => 'Pengawasan Kepatuhan Pendaftaran Perusahaan Wajib Belum Daftar (PWBD)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 1, 'box' => 'Box 03',
                'tanggal_berkas' => '2025-06-11', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00033',
                'nama_berkas' => 'Pendaftaran Pengemudi Angkutan Daring (BPU) - Jak-Lingko Rawamangun',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 2, 'box' => 'Box 03',
                'tanggal_berkas' => '2025-07-03', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00034',
                'nama_berkas' => 'Berkas Klaim JKM Peserta BPU - Ahli Waris: Nurlela (Alm. Supardi)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 3, 'box' => 'Box 04',
                'tanggal_berkas' => '2025-07-28', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00035',
                'nama_berkas' => 'Verifikasi Rekening Bank Gagal Salur Klaim - Bank Mandiri Pemuda',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 4, 'box' => 'Box 04',
                'tanggal_berkas' => '2025-08-19', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00036',
                'nama_berkas' => 'Surat Ketetapan Piutang Iuran (SKPI) - PT Bangun Cipta Pratama',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 5, 'box' => 'Box 04',
                'tanggal_berkas' => '2025-09-08', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00037',
                'nama_berkas' => 'Berkas Klaim JHT Masa Tunggu 10 Tahun (Klaim 10%) - PT Kalbe Farma Tbk',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 6, 'box' => 'Box 05',
                'tanggal_berkas' => '2025-10-14', 'status' => 'Dipinjam', // akan dibuat peminjaman aktif
            ],
            [
                'kode_berkas' => 'J0401B-00038',
                'nama_berkas' => 'Penyelidikan Kasus Khusus Fakta Lapangan JKK - Jatuh Ketinggian PT WIKA',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 1, 'box' => 'Box 05',
                'tanggal_berkas' => '2025-11-05', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00039',
                'nama_berkas' => 'Surat Kuasa Khusus (SKK) Kejaksaan Negeri Jaktim - Penagihan Piutang',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 2, 'box' => 'Box 05',
                'tanggal_berkas' => '2025-11-20', 'status' => 'Dipinjam', // akan dibuat peminjaman terlambat
            ],
            [
                'kode_berkas' => 'J0401B-00040',
                'nama_berkas' => 'Pendaftaran Yayasan Pendidikan - Yayasan Tarakanita Rawamangun',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 3, 'box' => 'Box 06',
                'tanggal_berkas' => '2025-12-02', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00041',
                'nama_berkas' => 'Berkas Klaim JHT Cacat Total Tetap - PT Dexa Medica (TK: Wawan D.)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 4, 'box' => 'Box 06',
                'tanggal_berkas' => '2025-12-18', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00042',
                'nama_berkas' => 'Surat Teguran Pertama Tunggakan Iuran (SP1) - PT Sejahtera Logistik',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 5, 'box' => 'Box 06',
                'tanggal_berkas' => '2026-01-08', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00043',
                'nama_berkas' => 'Berkas Klaim JP Manfaat Anak Berkala - Aditya Pratama (Alm. Danang)',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 6, 'box' => 'Box 07',
                'tanggal_berkas' => '2026-01-25', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00044',
                'nama_berkas' => 'Berkas Klaim JKM Peserta Jakon Masa Pemeliharaan - Flyover Pramuka',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 1, 'box' => 'Box 07',
                'tanggal_berkas' => '2026-02-04', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00045',
                'nama_berkas' => 'Pendaftaran Agen Penggerak Jamsostek (Perisai) - Wilayah Rawamangun',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 2, 'box' => 'Box 07',
                'tanggal_berkas' => '2026-02-15', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00046',
                'nama_berkas' => 'Klaim JHT Aplikasi JMO (Jamsostek Mobile) - Batch Validasi 02/2026',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 3, 'box' => 'Box 08',
                'tanggal_berkas' => '2026-02-28', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00047',
                'nama_berkas' => 'Laporan Hasil Audit Kepatuhan Dana Jamsostek oleh KAP Independen',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 4, 'box' => 'Box 08',
                'tanggal_berkas' => '2026-03-05', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00048',
                'nama_berkas' => 'Berkas Klaim JKK Penggantian Alat Bantu Protesa - RS Hermina Jatinegara',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 5, 'box' => 'Box 08',
                'tanggal_berkas' => '2026-03-12', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00049',
                'nama_berkas' => 'Perubahan Alamat & Susunan Direksi Perusahaan Peserta - PT Bentoel Prima',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 6, 'box' => 'Box 08',
                'tanggal_berkas' => '2026-03-20', 'status' => 'Tersedia',
            ],
            [
                'kode_berkas' => 'J0401B-00050',
                'nama_berkas' => 'Berkas Klaim JKM Karyawan Kontrak PKWT Selesai Tugas - PT ISS Indonesia',
                'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak D', 'baris' => 6, 'box' => 'Box 08',
                'tanggal_berkas' => '2026-03-28', 'status' => 'Tersedia',
            ],
        ];

        foreach ($dataBerkas as $item) {
            Arsip::create($item);
        }

        $this->info('50 arsip realistis BPJS berhasil disimpan ke database.');

        // 3. Buat transaksi peminjaman (3 sedang dipinjam tepat waktu, 3 terlambat, 4 sudah dikembalikan)
        $arsip4 = Arsip::where('kode_berkas', 'J0401B-00004')->first();
        $arsip9 = Arsip::where('kode_berkas', 'J0401B-00009')->first();
        $arsip17 = Arsip::where('kode_berkas', 'J0401B-00017')->first();
        $arsip28 = Arsip::where('kode_berkas', 'J0401B-00028')->first();
        $arsip37 = Arsip::where('kode_berkas', 'J0401B-00037')->first();
        $arsip39 = Arsip::where('kode_berkas', 'J0401B-00039')->first();

        // Peminjaman Aktif (Tepat Waktu - tenggat minggu depan)
        if ($arsip4) {
            Peminjaman::create([
                'arsip_id' => $arsip4->id,
                'nama_peminjam' => 'Dian Anggraini',
                'divisi' => 'Bidang Pelayanan & Klaim',
                'tanggal_pinjam' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Fajar Dwi Prasetyo',
            ]);
        }
        if ($arsip17) {
            Peminjaman::create([
                'arsip_id' => $arsip17->id,
                'nama_peminjam' => 'Rizky Pratama',
                'divisi' => 'Bidang Pelayanan & Klaim',
                'tanggal_pinjam' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Firman',
            ]);
        }
        if ($arsip37) {
            Peminjaman::create([
                'arsip_id' => $arsip37->id,
                'nama_peminjam' => 'Siti Nurhaliza',
                'divisi' => 'Bidang Keuangan',
                'tanggal_pinjam' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Fajar Dwi Prasetyo',
            ]);
        }

        // Peminjaman Terlambat (Overdue - agar tombol WhatsApp bisa diuji)
        if ($arsip9) {
            Peminjaman::create([
                'arsip_id' => $arsip9->id,
                'nama_peminjam' => 'Bayu Wicaksono',
                'divisi' => 'Bidang Pemasaran (Kepesertaan)',
                'tanggal_pinjam' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->subDays(5)->format('Y-m-d'), // lewat 5 hari
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Fajar Dwi Prasetyo',
            ]);
        }
        if ($arsip28) {
            Peminjaman::create([
                'arsip_id' => $arsip28->id,
                'nama_peminjam' => 'Mega Puspita',
                'divisi' => 'Bidang Pelayanan & Klaim',
                'tanggal_pinjam' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->subDays(3)->format('Y-m-d'), // lewat 3 hari
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Firman',
            ]);
        }
        if ($arsip39) {
            Peminjaman::create([
                'arsip_id' => $arsip39->id,
                'nama_peminjam' => 'Hendro Suwarno',
                'divisi' => 'Bidang Pengawasan & Pemeriksaan (Wasrik)',
                'tanggal_pinjam' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->subDays(7)->format('Y-m-d'), // lewat 7 hari
                'status' => 'Dipinjam',
                'disetujui_oleh' => 'Fajar Dwi Prasetyo',
            ]);
        }

        // Riwayat Peminjaman yang sudah dikembalikan (untuk melengkapi riwayat)
        $arsip1 = Arsip::where('kode_berkas', 'J0401B-00001')->first();
        if ($arsip1) {
            Peminjaman::create([
                'arsip_id' => $arsip1->id,
                'nama_peminjam' => 'Dian Anggraini',
                'divisi' => 'Bidang Pelayanan & Klaim',
                'tanggal_pinjam' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'tenggat_waktu' => Carbon::now()->subDays(13)->format('Y-m-d'),
                'tanggal_kembali' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'status' => 'Dikembalikan',
                'disetujui_oleh' => 'Fajar Dwi Prasetyo',
            ]);
        }

        // Catat aktivitas awal di log
        ActivityLog::create([
            'user_name' => 'Sistem',
            'action' => 'RESET_DATA',
            'description' => 'Sistem menginisialisasi 50 arsip fisik baru & peminjaman fiktif BPJS Ketenagakerjaan.',
        ]);

        $this->info('Data peminjaman aktif, terlambat, dan selesai berhasil disiapkan.');
        $this->info('Inisialisasi 50 data arsip selesai dengan sukses!');

        return Command::SUCCESS;
    }
}
