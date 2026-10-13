# 📂 F-Arsip BPJS

Aplikasi berbasis web yang digunakan untuk mengelola dan mengarsipkan dokumen BPJS secara digital dan terstruktur. Proyek ini dibangun untuk mendigitalisasi proses administrasi kampus agar lebih efisien.

---

## 🚀 Fitur Utama
*   **Manajemen Arsip:** Unggah, simpan, dan kategorikan dokumen BPJS.
*   **Pencarian Cepat:** Fitur filter data untuk menemukan arsip dalam hitungan detik.
*   **Pratinjau Dokumen:** Melihat isi dokumen PDF langsung di dalam aplikasi web.
*   **Antarmuka Responsif:** Nyaman diakses melalui laptop maupun perangkat mobile.

## 🛠️ Teknologi yang Digunakan
*   **Framework:** Laravel 11 / 10
*   **Frontend:** Blade Engine & Tailwind CSS
*   **Database:** MySQL (via Laragon)
*   **Tools:** Visual Studio Code & Git

## 💻 Cara Menjalankan Proyek Secara Lokal

Jika Anda ingin menjalankan proyek ini di laptop lain menggunakan **Laragon**, ikuti langkah berikut:

1. **Clone Repositori ini:**
   ```bash
   git clone https://github.com
   ```
2. Pindahkan folder proyek ke dalam direktori `C:/laragon/www/`.
3. Buka terminal di folder proyek dan instal dependensi:
   ```bash
   composer install
   npm install && npm run dev
   ```
4. Salin file konfigurasi `.env`:
   ```bash
   cp .env.example .env
   ```
5. Buat database baru di Laragon dengan nama `db_perpustakaan_kampus`, lalu sesuaikan pengaturannya di file `.env`.
6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```
7. Jalankan server lokal melalui Laragon atau terminal:
   ```bash
   php artisan serve
   ```
