<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArsipSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan sementara pengecekan relasi (Foreign Key)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Bersihkan tabel peminjaman dan arsip dari data lama
        DB::table('peminjamans')->truncate();
        DB::table('arsips')->truncate();

        // 3. Nyalakan kembali pengecekan relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Daftar 20 Arsip Fiktif Realistis
        $arsips = [
            // Ruang Arsip Kepesertaan (Baris maksimal 6)
            ['kode_berkas' => 'KPS-2026-001', 'nama_berkas' => 'Pendaftaran Kepesertaan Baru - PT Maju Bersama', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 1, 'box' => '12', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-002', 'nama_berkas' => 'Pendaftaran Kepesertaan Baru - CV Sentosa Abadi', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 2, 'box' => '12', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-003', 'nama_berkas' => 'Perubahan Data Pekerja - Rumah Sakit Sehat', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak A', 'baris' => 5, 'box' => '14', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-004', 'nama_berkas' => 'Penonaktifan Kepesertaan - PT Bangun Persada', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 1, 'box' => '05', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-005', 'nama_berkas' => 'Pendaftaran Mandiri (BPU) - Budi Santoso', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 5, 'box' => '08', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-006', 'nama_berkas' => 'Pendaftaran Mandiri (BPU) - Siti Aminah', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak B', 'baris' => 6, 'box' => '08', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-007', 'nama_berkas' => 'Rekonsiliasi Iuran - PT Teknologi Nusantara', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 2, 'box' => '21', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KPS-2026-008', 'nama_berkas' => 'Koreksi Iuran Ganda - CV Makmur Jaya', 'ruangan' => 'Ruang Arsip Kepesertaan', 'rak' => 'Rak C', 'baris' => 4, 'box' => '22', 'status' => 'Tersedia'],

            // Ruang Arsip Kecil (Baris maksimal 4)
            ['kode_berkas' => 'JHT-2026-001', 'nama_berkas' => 'Klaim Jaminan Hari Tua (JHT) - Rina Marlina', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 1, 'box' => '01', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JHT-2026-002', 'nama_berkas' => 'Klaim Jaminan Hari Tua (JHT) - Ahmad Fauzi', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak A', 'baris' => 2, 'box' => '01', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JKK-2026-001', 'nama_berkas' => 'Klaim Kecelakaan Kerja (JKK) - PT Maju Bersama', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 1, 'box' => '04', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JKK-2026-002', 'nama_berkas' => 'Klaim Kecelakaan Kerja (JKK) - CV Sentosa Abadi', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 2, 'box' => '04', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JKM-2026-001', 'nama_berkas' => 'Klaim Jaminan Kematian (JKM) - Ahli Waris Budi Santoso', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 3, 'box' => '05', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JKM-2026-002', 'nama_berkas' => 'Klaim Jaminan Kematian (JKM) - Ahli Waris Agus Salim', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak B', 'baris' => 4, 'box' => '05', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JP-2026-001', 'nama_berkas' => 'Klaim Jaminan Pensiun (JP) - Siti Aminah', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 1, 'box' => '10', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JP-2026-002', 'nama_berkas' => 'Klaim Jaminan Pensiun (JP) - Rudi Hermawan', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 2, 'box' => '10', 'status' => 'Tersedia'],
            ['kode_berkas' => 'JKP-2026-001', 'nama_berkas' => 'Klaim JKP Kehilangan Pekerjaan - Andi Wijaya', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak C', 'baris' => 4, 'box' => '12', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KEU-2026-001', 'nama_berkas' => 'SPJ Pengeluaran ATK Bulan Agustus 2026', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 1, 'box' => '15', 'status' => 'Tersedia'],
            ['kode_berkas' => 'KEU-2026-002', 'nama_berkas' => 'SPJ Biaya Rapat Koordinasi Cabang', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 2, 'box' => '15', 'status' => 'Tersedia'],
            ['kode_berkas' => 'UMM-2026-001', 'nama_berkas' => 'Surat Masuk - Dinas Tenaga Kerja Kota', 'ruangan' => 'Ruang Arsip Kecil', 'rak' => 'Rak D', 'baris' => 3, 'box' => '18', 'status' => 'Tersedia'],
        ];

        // Menyisipkan timestamp agar sesuai standar Laravel
        $now = Carbon::now();
        foreach ($arsips as &$arsip) {
            $arsip['created_at'] = $now;
            $arsip['updated_at'] = $now;
        }

        DB::table('arsips')->insert($arsips);
    }
}
