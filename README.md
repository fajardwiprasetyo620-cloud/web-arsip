# 📂 Sistem Informasi E-Arsip BPJS Ketenagakerjaan
### Kantor Cabang Jakarta Rawamangun

Aplikasi web enterprise modern untuk pengelolaan, digitalisasi, dan pemantauan tata kelola arsip fisik serta dokumen digital pada **BPJS Ketenagakerjaan Kantor Cabang Jakarta Rawamangun**.

Aplikasi ini dikembangkan untuk mempermudah pencarian berkas klaim (*JHT, JKK, JKM, JP*), pemantauan sirkulasi peminjaman dengan notifikasi pengingat WhatsApp, pemetaan kapasitas rak fisik, serta pencetakan label stiker QR code otomatis.

---

## ✨ Fitur-Fitur Unggulan

### 1. 🗄️ Manajemen Tata Kelola Fisik & Digital
- **Pemetaan Lokasi Fisik:** Validasi terstruktur untuk Ruangan (*Ruang Arsip Kecil* & *Ruang Arsip Kepesertaan*), Rak (A-D), Baris, dan Box.
- **Digitalisasi Berkas Privat:** Unggah dokumen scan/digital (PDF/gambar) hingga 10 MB per berkas dengan proteksi otentikasi login admin.
- **Filter Digitalisasi:** Penyaringan cepat berkas yang sudah di-scan PDF vs berkas yang masih berstatus fisik saja.

### 2. 📱 In-App QR Code Scanner & Cetak Label Stiker
- **Kamera QR Scanner In-App:** Pemindai QR code langsung menggunakan webcam laptop atau kamera smartphone tanpa perlu aplikasi scanner eksternal.
- **Cetak Label QR Berkas:** Mencetak stiker barcode per berkas dengan ukuran standar label.
- **Cetak Stiker Box Fisik:** Mencetak label resmi penampang depan box arsip lengkap dengan rentang periode tanggal berkas di dalamnya.
- **Katalog Box Digital:** Memindai QR pada box fisik langsung menampilkan seluruh daftar dokumen di dalam box tersebut.

### 3. 🔄 Sirkulasi Peminjaman & Pengingat WhatsApp
- **Transaksi Peminjaman Berkas:** Pengelompokan peminjaman per karyawan dengan masa tenggat (*due date*) dan pencegahan peminjaman ganda.
- **Otomasi Pesan WhatsApp (Overdue Reminder):** Tombol klik langsung untuk mengirim pesan WhatsApp pengingat keterlambatan kepada peminjam.
- **Cetak Berita Acara Peminjaman (BAP):** Cetak lembar resmi A4 serah-terima peminjaman arsip ber-kop instansi lengkap dengan 3 kolom tanda tangan resmi (Peminjam, Petugas Arsip, dan Kepala Bidang).

### 4. 📊 Dashboard & Monitoring Kapasitas Rak Fisik
- **Kartu Statistik Interaktif:** Total berkas, berkas tersedia, berkas dipinjam, dan berkas terlambat.
- **Interactive Charts (Chart.js):** Grafik perbandingan status dokumen (Donat) dan sebaran arsip per ruangan (Batang).
- **Storage Heatmap & Shelf Visualizer:** Visualisasi kapasitas keterisian baris dan box pada Rak A s/d Rak D dengan indikator persentase dinamis.

### 5. 🛡️ Keamanan, Audit & Backup
- **Audit Log Aktivitas:** Pencatatan otomatis setiap aktivitas user dengan fitur ekspor ke Excel.
- **1-Klik Backup Database:** Mengunduh dump basis data SQL langsung melalui menu navigasi admin atau terjadwal via CLI (`php artisan arsip:backup`).
- **Responsive Web Design:** Nyaman diakses di layar monitor PC, laptop, tablet, hingga smartphone (HP) tanpa scrollbar horizontal yang mengganggu.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | [Laravel 10 / 11](https://laravel.com) (PHP 8.2+) |
| **Frontend Styling** | [Tailwind CSS 3.4](https://tailwindcss.com) & [Blade Engine](https://laravel.com/docs/blade) |
| **Interaktivitas UI** | [Alpine.js 3](https://alpinejs.dev) & [SweetAlert2](https://sweetalert2.github.io/) |
| **QR Engine** | [SimpleSoftwareIO Simple-QRCode](https://www.simplesoftware.io/#/docs/simple-qrcode) & [Html5-QRCode](https://github.com/mebjas/html5-qrcode) |
| **PDF & Excel** | [Barryvdh Laravel-DomPDF](https://github.com/barryvdh/laravel-dompdf) & [Maatwebsite Excel 3.1](https://maatwebsite.nl) |
| **Visualisasi Grafik** | [Chart.js 4](https://www.chartjs.org/) |
| **Database Engine** | MySQL / MariaDB (Didukung Indexing Teroptimasi) |
| **Pengujian (Testing)** | PHPUnit dengan **93 Automated Tests** (100% Pass) |

---

## 🚀 Panduan Instalasi Lokal

### 1. Klon Repositori
```bash
git clone https://github.com/username/web-arsip.git
cd web-arsip
```

### 2. Instal Dependensi PHP & Frontend
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file template `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database Anda di dalam file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_arsip
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate
```

### 5. Buat Tautan Penyimpanan File Privat
```bash
php artisan storage:link
```

### 6. Isi Data Awal & Data Uji Fiktif (Opsional)
Jalankan perintah khusus untuk mereset dan membuat 50 berkas arsip realistis BPJS Ketenagakerjaan beserta data peminjaman uji coba:
```bash
php artisan arsip:reset-seed
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Buka browser dan akses aplikasi melalui `http://127.0.0.1:8000` atau via Laragon `http://web-arsip.test`.

---

## 🔑 Kredensial Login Administrator (Demo)

| Email | Password | Peran |
| :--- | :--- | :--- |
| `fajar@gmail.com` | `password` | Administrator (Tim IT) |
| `firman@gmail.com` | `password` | Petugas Pengelola Arsip |

---

## 🧪 Eksekusi Pengujian Otomatis (Automated Tests)

Aplikasi dilengkapi suite pengujian otomatis yang komprehensif:
```bash
php artisan test
```
Hasil terakhir: **93 tests passed (264 assertions)** tanpa satupun eror.

---

## 📖 Panduan Penggunaan Lengkap
Untuk panduan operasional langkah-demi-langkah, alur SOP instansi, dan kebutuhan laporan magang/skripsi, silakan baca:
👉 [**PANDUAN-PENGGUNA-E-ARSIP.md**](PANDUAN-PENGGUNA-E-ARSIP.md)

---

## 👨‍💻 Pengembang
Dikembangkan oleh **Fajar Dwi Prasetyo** (Tim IT & Kearsipan)  
BPJS Ketenagakerjaan Kantor Cabang Jakarta Rawamangun.
