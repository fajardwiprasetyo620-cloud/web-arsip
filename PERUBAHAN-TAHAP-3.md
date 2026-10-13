# Perbaikan E-Arsip - Tahap 3 + Kartu "Terlambat"

Pasang SETELAH Tahap 1 dan 2 (file ArsipController, Arsip.php, index.blade.php, dan web.php di sini sudah memuat perubahan Tahap 1-2).

## Cara memasang
1. Backup folder project (cukup salin foldernya).
2. Ekstrak zip ini ke folder project (yang berisi file `artisan`), pilih Replace / Timpa semua.
3. Jalankan di Terminal Laragon:
       php artisan optimize:clear
4. (Disarankan) Set zona waktu agar hitungan "terlambat" pas dengan hari di Indonesia.
   Buka config/app.php, ubah baris:
       'timezone' => 'UTC',
   menjadi:
       'timezone' => 'Asia/Jakarta',
   lalu jalankan lagi: php artisan optimize:clear

## Daftar file
| File | Perubahan |
|---|---|
| app/Http/Controllers/DashboardController.php | BARU - semua data dashboard disiapkan di sini (satu query untuk angka ringkasan) |
| app/Models/Arsip.php | Tambah scopeFilter (search, ruangan, status) |
| app/Models/Peminjaman.php | Tambah scopeTerlambat |
| app/Http/Controllers/ArsipController.php | katalog() dan index() memakai scopeFilter + paginate(15); pesan hasil import menampilkan jumlah masuk/dilewati |
| app/Imports/ArsipImport.php | Ambil kode yang sudah ada sekali di awal (tanpa query per baris), baca kolom BARIS, deteksi duplikat di dalam file, batch insert |
| routes/web.php | Route /dashboard memakai DashboardController |
| resources/views/dashboard.blade.php | Kartu "Peminjaman Terlambat" + tabel 5 terlambat paling lama; query di view dihapus |
| resources/views/katalog.blade.php | Navigasi halaman (pagination) |
| resources/views/arsip/index.blade.php | Navigasi halaman (pagination) |
| resources/views/arsip/template_excel.blade.php | Tambah kolom BARIS; contoh memakai nama ruangan yang valid |

## Yang berubah dari sisi pemakaian
- Katalog dan halaman arsip menampilkan 15 data per halaman, diurutkan menurut kode berkas.
- Kotak "pilih semua" di halaman arsip hanya memilih data di halaman yang sedang tampil.
- Template Excel punya kolom baru BARIS. Template lama tetap bisa dipakai (baris otomatis 1).
- "Terlambat" = status Dipinjam, punya tenggat, dan tenggatnya sebelum hari ini.

## Cek setelah pasang
- [ ] Dashboard tampil dengan 4 kartu; kartu merah menunjukkan jumlah terlambat
- [ ] Buat peminjaman dengan tenggat kemarin: muncul di kartu dan tabel terlambat; setelah dikembalikan, hilang
- [ ] Katalog dan halaman arsip: pindah halaman, pencarian dan filter tetap terbawa
- [ ] Download template Excel baru, isi 2-3 baris, import: pesan menampilkan jumlah masuk/dilewati
- [ ] Import file yang sama sekali lagi: semua baris dilewati (tidak ada data ganda)
