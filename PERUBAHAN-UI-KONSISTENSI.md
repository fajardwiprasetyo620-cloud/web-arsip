# Perbaikan UI/UX: Konsistensi Warna, Font, dan Bahasa

## Yang ditemukan dan diperbaiki

### 1. Sisa warna indigo (bukan emerald) di beberapa tempat
Sebagian besar halaman admin sudah konsisten emerald/hijau, tapi beberapa
komponen bawaan Laravel Breeze masih memakai warna ungu-biru (indigo) bawaan:
- Checkbox "Ingat saya" di halaman login
- Link "Lupa kata sandi?" (warna fokus/klik)
- Menu navigasi versi HP (saat salah satu menu aktif)
- Tombol sekunder (mis. tombol "Batal") -- warna fokusnya
- Halaman verifikasi email dan edit profil (link kecil di bawah form)

Semuanya sekarang emerald, senada dengan seluruh aplikasi.

### 2. Teks bahasa Inggris yang tersisa
Aplikasi ternyata SUDAH memakai sistem terjemahan resmi Laravel (`__('...')`),
cuma belum ada file terjemahan Indonesianya -- jadi tampil apa adanya dalam
bahasa Inggris ("Remember me", "Log Out", "Forgot your password?", dst).
Dibuatkan satu file (`lang/id.json`) yang menerjemahkan ~30 string sekaligus:
checkbox, tombol, judul halaman profil, pesan konfirmasi hapus akun, dan lain-lain.

### 3. Font masih bawaan Breeze (Figtree), dimuat dari CDN bunny.net
Diganti ke **Plus Jakarta Sans** -- dipilih karena ini sistem milik instansi
yang berbasis di Jakarta, jadi lebih punya identitas dibanding font generik.
Dipasang lewat **npm** (file font ikut dibundel ke aplikasi), BUKAN dari CDN,
konsisten dengan prinsip yang sama seperti Chart.js dan SweetAlert2 sebelumnya
-- tetap tampil normal walau internet kantor bermasalah.

### 4. `tailwind.config.js` ternyata rusak
Bagian `theme` dan `plugins` hilang total dari file ini (kemungkinan
terhapus tidak sengaja saat mengedit `darkMode`). Akibatnya:
- Plugin `@tailwindcss/forms` sudah di-import tapi TIDAK PERNAH aktif,
  jadi checkbox/radio/select belum mendapat styling rapi dari plugin ini.
- Tidak ada tempat untuk menambahkan font kustom.

Sudah dilengkapi kembali, plugin forms sekarang benar-benar aktif.

## File yang berubah
| File | Perubahan |
|---|---|
| package.json | Tambah @fontsource-variable/plus-jakarta-sans |
| tailwind.config.js | Dilengkapi ulang: theme.extend.fontFamily + plugins: [forms] |
| resources/css/app.css | Import font Plus Jakarta Sans |
| config/app.php | locale diganti dari 'en' ke 'id' |
| lang/id.json | BARU -- terjemahan ~30 string bawaan Breeze |
| resources/views/layouts/app.blade.php | Hapus link CDN font bunny.net |
| resources/views/layouts/guest.blade.php | Hapus link CDN font bunny.net |
| resources/views/auth/login.blade.php | indigo -> emerald (checkbox, link lupa kata sandi) |
| resources/views/auth/register.blade.php | indigo -> emerald (halaman ini sendiri sudah tidak aktif/404, tapi dirapikan juga) |
| resources/views/auth/verify-email.blade.php | indigo -> emerald |
| resources/views/components/secondary-button.blade.php | indigo -> emerald |
| resources/views/components/responsive-nav-link.blade.php | indigo -> emerald (menu navigasi versi HP) |
| resources/views/profile/partials/update-profile-information-form.blade.php | indigo -> emerald |

## Cara pasang (urutan penting)
1. Ekstrak zip ke folder project, pilih Replace.
2. Jalankan di Terminal Laragon:
       npm install
   (mengunduh font Plus Jakarta Sans, sekali saja)
3. Jalankan:
       npm run build
4. Jalankan:
       php artisan optimize:clear

## Cara mengecek
1. Buka halaman manapun -- font harus terlihat sedikit berbeda (Plus Jakarta
   Sans, lebih modern) dibanding sebelumnya.
2. Buka halaman login: centang "Ingat saya" -- warna centangnya sekarang
   hijau (emerald), bukan ungu. Teks "Remember me"/"Forgot your password?"
   sekarang berbahasa Indonesia.
3. Buka menu di HP (hamburger menu): menu yang aktif sekarang bergaris hijau,
   bukan ungu.
4. Buka halaman Profil: cek tombol dan link kecil di bagian hapus akun --
   semuanya emerald dan sudah berbahasa Indonesia.
5. Jalankan `php artisan test` -- harus tetap 34 PASS (perubahan ini murni
   tampilan dan terjemahan, tidak menyentuh logika).

## Catatan
Halaman Register dan Verifikasi Email sebenarnya sudah tidak aktif (sejak
Tahap 1, route-nya dihapus / User model tidak mengaktifkan verifikasi email).
Perubahan warna di kedua file itu tetap dilakukan untuk kerapian kode, meski
praktiknya tidak akan terlihat pengguna.
