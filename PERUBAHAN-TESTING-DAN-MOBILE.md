# Feature Test (alur pinjam-kembali) + Tampilan Mobile Katalog

## Bagian A: Feature Test

### File
| File | Isi |
|---|---|
| database/factories/ArsipFactory.php | BARU - membuat data arsip contoh untuk testing |
| database/factories/PeminjamanFactory.php | BARU - membuat data peminjaman contoh untuk testing |
| tests/Feature/PeminjamanFlowTest.php | BARU - 6 skenario: pinjam berhasil, tolak pinjam dobel, kembali berhasil, tolak kembali dobel, tolak hapus riwayat aktif, hapus riwayat yang sudah selesai |
| tests/Feature/ArsipDeletionTest.php | BARU - 3 skenario: tolak hapus arsip dipinjam, hapus arsip tersedia, hapus massal melewati yang dipinjam |

### Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Buka file `phpunit.xml` di folder project (BUKAN yang ada di dalam folder `vendor`). Cari dua baris ini:
       <!-- <env name="DB_CONNECTION" value="sqlite"/> -->
       <!-- <env name="DB_DATABASE" value=":memory:"/> -->
   Hapus tanda `<!--` dan `-->` di awal dan akhir baris, sehingga jadi:
       <env name="DB_CONNECTION" value="sqlite"/>
       <env name="DB_DATABASE" value=":memory:"/>
   Ini membuat test berjalan di database sementara di memori, jadi TIDAK menyentuh database asli Anda sama sekali.
3. Jalankan di Terminal Laragon:
       php artisan test
   (kalau perintah ini tidak dikenali, coba: `.\vendor\bin\phpunit`)

### Yang diharapkan
Semua test menampilkan tanda centang hijau / PASS. Kalau ada yang gagal (FAIL), itu menunjukkan bagian alur pinjam-kembali yang perlu dicek ulang -- kirim pesan errornya ke sini.

### Kalau muncul "could not find driver" terkait sqlite
Berarti ekstensi PHP sqlite belum aktif. Buka `php.ini` (dari Laragon: klik kanan ikon Laragon > PHP > php.ini), cari baris `;extension=pdo_sqlite` dan `;extension=sqlite3`, hapus tanda `;` di depan keduanya, simpan, lalu restart Laragon dan ulangi `php artisan test`.

## Bagian B: Tampilan Mobile Katalog Publik

### File
| File | Perubahan |
|---|---|
| resources/views/katalog.blade.php | Tabel hanya tampil di layar medium ke atas (tablet/desktop). Di HP, data ditampilkan sebagai kartu yang lebih mudah dibaca, lengkap dengan navigasi halaman |

Halaman detail (`katalog-detail.blade.php`) tidak diubah karena sudah didesain sebagai kartu sempit yang cocok untuk HP.

### Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Jalankan: php artisan optimize:clear

### Cara mengecek
Buka halaman katalog publik dari HP (atau di browser desktop, tekan F12 lalu aktifkan mode tampilan HP / device toolbar). Data harus tampil sebagai daftar kartu vertikal, bukan tabel yang harus digeser ke samping. Coba juga cari/filter data dan pindah halaman, memastikan hasilnya tetap konsisten.
