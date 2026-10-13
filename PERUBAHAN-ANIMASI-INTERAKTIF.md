# Animasi & Interaktivitas + Penyegaran Gaya Dashboard (terinspirasi "Geex")

PENTING: pasang zip Tom Select (tom-select-npm.zip) DULU kalau belum, baru
zip ini. File resources/css/app.css di zip ini sudah menyertakan baris
import Tom Select, jadi aman ditimpa dalam urutan apa pun -- tapi kalau Anda
belum sempat pasang Tom Select sama sekali, pasang itu dulu.

## 1. Notifikasi otomatis hilang (toast)
Pesan sukses/peringatan/error yang tadinya kotak statis di atas halaman,
sekarang muncul sebagai notifikasi kecil di pojok kanan atas, hilang sendiri
setelah 3,5 detik (bisa dihentikan sementara dengan mengarahkan kursor ke
atasnya). Pakai SweetAlert2 yang sudah terpasang -- tidak ada library baru.
Error validasi per kolom form TETAP sebagai kotak statis seperti sebelumnya
(sengaja tidak dibuat toast, supaya tidak hilang sebelum sempat diperbaiki).

## 2. Indikator loading saat Export PDF / Import Excel
- Tombol "Cetak Semua" (arsip & peminjaman): saat diklik, ikon berubah jadi
  spinner berputar dan teks berubah jadi "Menyiapkan..." selama proses.
- Tombol "Mulai Import": saat diklik, tombol & "Batal" otomatis nonaktif
  sementara, ikon berubah spinner, teks jadi "Memproses...", supaya tidak
  bisa diklik dobel saat file sedang diproses.

## 3. Angka di kartu Dashboard "naik" pelan saat halaman dibuka
Ke-4 kartu (Total, Tersedia, Dipinjam, Terlambat) sekarang menampilkan
animasi angka dari 0 naik ke nilai aslinya dalam waktu kurang dari 1 detik
saat halaman pertama dibuka. Dikerjakan pakai Alpine.js yang sudah ada,
tidak ada library baru.

## 4. Penyegaran gaya kartu Dashboard (terinspirasi arah "Geex")
Bukan tiruan persis (kami hanya melihat gambarnya sebagai referensi, bukan
membedah desainnya), tapi mengambil arah umum "modern elegant dashboard":
- Sudut kartu lebih membulat (rounded-xl -> rounded-2xl)
- Bayangan lebih lembut dan menyebar, bukan bayangan tajam bawaan Tailwind
- Badge ikon sekarang bulat penuh (lingkaran), bukan kotak membulat
- Kartu sedikit "terangkat" (hover:-translate-y-0.5) saat kursor diarahkan
- WARNA TIDAK DIUBAH -- tetap emerald/biru/amber/merah seperti sekarang,
  sesuai permintaan Anda untuk tetap menyesuaikan dengan web yang ada

## File yang berubah
| File | Perubahan |
|---|---|
| resources/views/components/flash.blade.php | Diganti total: toast SweetAlert2, bukan kotak statis |
| resources/views/dashboard.blade.php | 4 kartu: gaya baru + animasi angka naik |
| resources/views/arsip/index.blade.php | Loading state: tombol Cetak Semua & Mulai Import |
| resources/views/peminjaman/index.blade.php | Loading state: tombol Cetak Semua Laporan |
| resources/css/app.css | Tambah aturan CSS untuk [x-cloak] (mencegah "kedip" ikon) |

## Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Terminal Laragon:
       npm run build
   (tidak perlu npm install lagi -- tidak ada dependency baru di langkah ini)
3. php artisan optimize:clear

## Cara mengecek
1. Tambah/edit/hapus data apa saja -- notifikasi hijau/merah sekarang
   muncul di pojok kanan atas dan hilang sendiri, bukan kotak statis lagi.
2. Buka Dashboard -- perhatikan angka di 4 kartu "menghitung naik" sesaat
   setelah halaman terbuka, dan kartunya sedikit lebih membulat/lembut.
3. Klik "Cetak Semua" di halaman Arsip atau Peminjaman -- ikon berubah
   jadi spinner sesaat sebelum file PDF terunduh.
4. Buka form Import Excel, pilih file, klik "Mulai Import" -- tombol
   langsung berubah jadi "Memproses..." dengan ikon berputar.
5. php artisan test -- harus tetap 34 PASS (semua perubahan ini murni
   tampilan, tidak menyentuh logika backend).
