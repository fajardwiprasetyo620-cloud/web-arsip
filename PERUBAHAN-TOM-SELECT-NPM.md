# Tom Select (dropdown pencarian arsip) lewat npm, bukan CDN

## Kenapa
Konsisten dengan Chart.js dan SweetAlert2 sebelumnya: kotak pencarian arsip
di halaman "Tambah Peminjaman" sebelumnya memuat library Tom Select dari
internet (CDN) setiap kali halaman dibuka. Sekarang dibundel jadi bagian
dari file aplikasi sendiri, tetap berfungsi walau internet kantor bermasalah.

## File yang berubah
| File | Perubahan |
|---|---|
| package.json | Tambah dependency tom-select |
| resources/js/app.js | Import Tom Select (build "complete", termasuk plugin remove_button), didaftarkan ke window.TomSelect |
| resources/css/app.css | Import CSS bawaan Tom Select |
| resources/views/peminjaman/create.blade.php | Hapus 2 tag CDN (link CSS + script JS). Style kustom (.ts-control) dan kode `new TomSelect(...)` TIDAK berubah |

## Cara pasang (urutan penting)
1. Ekstrak zip ke folder project, pilih Replace.
2. Terminal Laragon, masuk folder project, jalankan:
       npm install
3. Jalankan:
       npm run build
4. Jalankan:
       php artisan optimize:clear

## Cara mengecek
1. Buka menu Peminjaman > Tambah Peminjaman. Kotak pencarian arsip harus
   tetap berfungsi seperti biasa: bisa diketik, hasil tersaring otomatis,
   dan tampilannya (warna tag biru, padding, dsb) tetap sama persis.
2. Matikan Wi-Fi sebentar, buka ulang halaman ini -- kotak pencarian harus
   tetap muncul dan berfungsi (sebelumnya akan gagal tanpa internet).
3. php artisan test -- harus tetap 34 PASS (perubahan ini tidak menyentuh
   logika backend sama sekali).
