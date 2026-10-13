# Poin 4: Chart.js + SweetAlert2 lewat npm, dan Poin 5: Pecah ArsipController

## Bagian A: Chart.js dan SweetAlert2 lewat npm

### Kenapa
Sebelumnya dashboard dan halaman peminjaman memuat Chart.js/SweetAlert2 dari
internet (CDN) setiap kali halaman dibuka. Kalau internet kantor bermasalah,
grafik dashboard dan konfirmasi hapus ikut tidak berfungsi. Sekarang keduanya
"dibundel" jadi bagian dari file JS aplikasi sendiri, sehingga tidak lagi
bergantung ke internet luar.

### File yang berubah
| File | Perubahan |
|---|---|
| package.json | Tambah dependency chart.js dan sweetalert2 |
| resources/js/app.js | Import Chart.js dan SweetAlert2, didaftarkan ke `window.Chart` / `window.Swal` supaya tetap bisa dipanggil dari script biasa di Blade |
| resources/views/dashboard.blade.php | Hapus tag `<script src="cdn.../chart.js">` |
| resources/views/peminjaman/index.blade.php | Hapus tag `<script src="cdn.../sweetalert2">` |

### Cara pasang (WAJIB ikuti urutan ini)
1. Ekstrak zip ke folder project, pilih Replace.
2. Buka Terminal Laragon, masuk ke folder project, lalu jalankan:
       npm install
   (mengunduh chart.js dan sweetalert2 ke folder node_modules — sekali saja)
3. Jalankan:
       npm run build
   (menggabungkan semuanya jadi file JS/CSS final di folder public/build)
4. Jalankan:
       php artisan optimize:clear

### Cara mengecek
- Buka Dashboard: grafik donat dan batang harus tetap muncul seperti biasa.
- Buka menu Peminjaman, coba hapus riwayat: kotak konfirmasi bergaya (bukan kotak polos bawaan browser) harus tetap muncul.
- Kalau mau membuktikan ini sudah tidak bergantung internet: matikan Wi-Fi/data sebentar, refresh halaman Dashboard dan Peminjaman. Sebelumnya grafik/SweetAlert akan gagal muncul; sekarang seharusnya tetap normal.

### Catatan
Ada satu library lain yang masih dari CDN: **Tom Select** (dropdown pencarian
arsip) di halaman "Tambah Peminjaman". Ini di luar permintaan Anda (Chart.js +
SweetAlert2 saja), jadi sengaja tidak diubah. Kalau nanti mau dipindah ke npm
juga, tinggal bilang.

## Bagian B: Memecah ArsipController

### Kenapa
`ArsipController` sebelumnya menampung 288 baris untuk 5 tanggung jawab
berbeda (katalog publik, CRUD arsip, import Excel, export PDF, log aktivitas).
Ini murni kerapian, tidak mengubah fitur atau tampilan sama sekali.

### Controller baru
| Controller | Isi |
|---|---|
| KatalogController | Katalog publik (index, show) |
| ArsipController | CRUD arsip, file dokumen privat, riwayat hapus, hapus massal, QR code (sekarang 207 baris) |
| ArsipImportController | Import Excel + download template |
| ArsipExportController | Export PDF (semua data / terpilih) |
| LogAktivitasController | Log aktivitas |

### PENTING: semua nama route TIDAK berubah
`route('arsip.index')`, `route('katalog.show', ...)`, `route('arsip.pdf')`, dan
seterusnya tetap sama persis seperti sebelumnya. Jadi seluruh file `.blade.php`
yang sudah ada TIDAK perlu diubah sama sekali — cukup ganti `routes/web.php`
dan tambahkan controller barunya.

### File yang berubah
| File | Perubahan |
|---|---|
| app/Http/Controllers/KatalogController.php | BARU |
| app/Http/Controllers/ArsipImportController.php | BARU |
| app/Http/Controllers/ArsipExportController.php | BARU |
| app/Http/Controllers/LogAktivitasController.php | BARU |
| app/Http/Controllers/ArsipController.php | Dipersingkat, fungsi yang dipindah dihapus dari sini |
| routes/web.php | Diarahkan ke controller yang baru; nama route tidak berubah |

### Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Jalankan: php artisan optimize:clear

### Cara mengecek
Jalankan `php artisan test` — semua 34 test harus tetap PASS (test ini memakai
nama route, bukan nama controller, jadi tidak akan rusak oleh pemecahan ini).
Lalu coba buka semua menu satu per satu: katalog publik, tambah/edit/hapus
arsip, import Excel, export PDF, riwayat hapus, QR code, dan log aktivitas —
semuanya harus tetap berjalan seperti biasa.
