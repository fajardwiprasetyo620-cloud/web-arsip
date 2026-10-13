# Perbaikan atas hasil `php artisan test`

Hasil sebelumnya: 30 passed, 4 failed. Ini perbaikannya per kegagalan.

## 1-2. Tests\Feature\Auth\RegistrationTest (2 gagal)
**Bukan bug.** Test bawaan Laravel ini mengasumsikan halaman /register masih aktif.
Padahal di Tahap 1 kita SENGAJA mematikannya (celah keamanan: dulu siapa pun bisa
bikin akun admin sendiri). Jadi yang gagal itu justru test yang sudah usang.

File ini saya tulis ulang supaya membuktikan penonaktifannya benar-benar berfungsi:
mengecek /register mengembalikan 404 dan tidak ada user baru yang tercipta.

## 3. Tests\Feature\ExampleTest (gagal, status 500)
Dua penyebab sekaligus:
- Test ini tidak memakai RefreshDatabase, jadi tabel-tabel arsip belum dibuat saat
  test jalan → query error.
- Halaman katalog memanggil `@vite(...)` untuk memuat CSS/JS, tapi asset belum
  di-build (`npm run build`) di lingkungan testing → error.

Perbaikan: tambahkan RefreshDatabase di ExampleTest, dan matikan pemanggilan Vite
khusus saat testing lewat `tests/TestCase.php` (berlaku otomatis untuk semua test).

## 4. PeminjamanFlowTest > arsip yang sudah dipinjam tidak bisa dipinjam lagi (gagal)
**Ini bug di test saya sendiri**, bukan di kode aplikasi Anda. Test ini menyiapkan
arsip berstatus "Dipinjam" tapi lupa membuat data peminjaman yang menyebabkan
status itu. Akibatnya waktu dicek "harus ada 1 peminjaman lama", yang ada malah 0.
Sudah saya perbaiki: peminjaman lamanya sekarang benar-benar dibuat dulu.

## File yang berubah
| File | Perubahan |
|---|---|
| tests/TestCase.php | Matikan @vite() otomatis di semua test |
| tests/Feature/ExampleTest.php | Tambah RefreshDatabase |
| tests/Feature/Auth/RegistrationTest.php | Ditulis ulang: membuktikan /register memang dimatikan |
| tests/Feature/PeminjamanFlowTest.php | Perbaiki bug test (peminjaman lama sekarang dibuat) |
| tests/Feature/ArsipDeletionTest.php | Tidak berubah (disertakan agar zip lengkap) |

## Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Jalankan:
       php artisan test

## Hasil yang diharapkan
34 test, semuanya PASS (0 failed). Kalau ada yang masih gagal, kirim screenshot
hasilnya seperti sebelumnya.
