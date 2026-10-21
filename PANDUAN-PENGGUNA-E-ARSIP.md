# BUKU PANDUAN PENGGUNA (USER MANUAL & SOP SISTEM)
## SISTEM INFORMASI MANAJEMEN E-ARSIP BERBASIS WEB
### BPJS Ketenagakerjaan Kantor Cabang Jakarta Rawamangun

---

## DAFTAR ISI
1. [Pendahuluan & Gambaran Umum Sistem](#1-pendahuluan--gambaran-umum-sistem)
2. [Hak Akses & Peran Pengguna (User Roles)](#2-hak-akses--peran-pengguna-user-roles)
3. [Panduan Akses Publik & Karyawan (Tanpa Login)](#3-panduan-akses-publik--karyawan-tanpa-login)
   - [3.1 Pencarian Berkas di E-Katalog Publik](#31-pencarian-berkas-di-e-katalog-publik)
   - [3.2 In-App QR Code Scanner (Kamera & Galeri)](#32-in-app-qr-code-scanner-kamera--galeri)
   - [3.3 Katalog Digital Box Fisik](#33-katalog-digital-box-fisik)
4. [Panduan Operasional Administrator / Petugas Arsip (Wajib Login)](#4-panduan-operasional-administrator--petugas-arsip-wajib-login)
   - [4.1 Dashboard Utama & Visual Monitoring Kapasitas Rak](#41-dashboard-utama--visual-monitoring-kapasitas-rak)
   - [4.2 Manajemen Berkas Fisik & Digital (CRUD)](#42-manajemen-berkas-fisik--digital-crud)
   - [4.3 Filter Digitalisasi Berkas (Scan PDF vs Fisik)](#43-filter-digitalisasi-berkas-scan-pdf-vs-fisik)
   - [4.4 Import & Export Data Excel](#44-import--export-data-excel)
   - [4.5 Cetak Stiker QR Code Berkas & Box](#45-cetak-stiker-qr-code-berkas--box)
   - [4.6 Sirkulasi Peminjaman & Pengembalian Berkas](#46-sirkulasi-peminjaman--pengembalian-berkas)
   - [4.7 Otomasi Pengingat WhatsApp (Overdue Reminder)](#47-otomasi-pengingat-whatsapp-overdue-reminder)
   - [4.8 Cetak Berita Acara Peminjaman (BAP) Resmi](#48-cetak-berita-acara-peminjaman-bap-resmi)
   - [4.9 Audit Log Aktivitas & Export Excel](#49-audit-log-aktivitas--export-excel)
   - [4.10 Pencadangan Basis Data (1-Click Database Backup)](#410-pencadangan-basis-data-1-click-database-backup)
5. [Spesifikasi Teknis & Kualitas Sistem](#5-spesifikasi-teknis--kualitas-sistem)
   - [5.1 Arsitektur & Teknologi](#51-arsitektur--teknologi)
   - [5.2 Pengujian Otomatis (Automated Testing)](#52-pengujian-otomatis-automated-testing)

---

## 1. Pendahuluan & Gambaran Umum Sistem

Sistem Informasi E-Arsip BPJS Ketenagakerjaan Kantor Cabang Jakarta Rawamangun merupakan aplikasi berbasis web yang dirancang khusus untuk memodernisasi tata kelola kearsipan dokumen klaim dan kepesertaan. 

Sistem ini mengintegrasikan **pengelolaan fisik** (Ruangan, Rak, Baris, dan Box arsip) dengan **digitalisasi dokumen** (PDF/dokumen digital terenkripsi privat), dilengkapi fitur otomatisasi mutakhir seperti **Quick Response (QR) Code**, **pemindai kamera in-app**, **sirkulasi peminjaman dengan pengingat WhatsApp**, serta **monitoring kapasitas rak fisik**.

---

## 2. Hak Akses & Peran Pengguna (User Roles)

Sistem membagi akses menjadi 2 tingkat otorisasi:

| Peran Pengguna | Tingkat Akses | Hak Fungsional Utama |
| :--- | :--- | :--- |
| **Karyawan / Staf Internal** | Tanpa Login (*Publik Internal*) | • Mencari data arsip klaim/kepesertaan.<br>• Melihat ketersediaan fisik berkas (*Tersedia / Dipinjam*).<br>• Memindai QR Code via kamera HP/Laptop.<br>• Melihat daftar isi box arsip fisik. |
| **Administrator / Petugas Arsip** | Wajib Login (*Autentikasi Aman*) | • Seluruh hak akses publik.<br>• Dashboard pemantauan kapasitas rak & grafik.<br>• Tambah, ubah, hapus arsip & upload PDF scan hingga 10MB.<br>• Cetak label stiker QR code berkas & stiker box.<br>• Pencatatan peminjaman, cetak Berita Acara (BAP), dan konfirmasi pengembalian.<br>• Pengiriman pengingat WhatsApp ke peminjam terlambat.<br>• Export/Import data Excel & download cadangan database SQL. |

---

## 3. Panduan Akses Publik & Karyawan (Tanpa Login)

### 3.1 Pencarian Berkas di E-Katalog Publik
1. Buka alamat utama web: `http://web-arsip.test/katalog`.
2. Masukkan kata kunci pencarian pada kolom pencarian (dapat berupa **Nomor/Kode Berkas**, **Nama Peserta/Perusahaan**, atau **Nomor Box**).
3. Anda dapat memfilter hasil berdasarkan **Ruangan** atau **Status Ketersediaan** (*Tersedia* atau *Dipinjam*).
4. Klik tombol **Cari Arsip**.
5. Klik **Lihat Detail** pada baris berkas untuk melihat informasi detail letak fisik dan histori ketersediaan.

### 3.2 In-App QR Code Scanner (Kamera & Galeri)
Fitur pemindai QR langsung di dalam aplikasi web tanpa memerlukan aplikasi barcode pihak ketiga:
1. Klik tombol **Scan QR** pada header navigasi publik atau kunjungi `http://web-arsip.test/scan-qr`.
2. **Metode 1 (Kamera Langsung):** Berikan izin akses kamera browser. Arahkan kamera HP/webcam laptop ke stiker barcode fisik. Sistem akan otomatis mendeteksi dan langsung membuka berkas atau box terkait.
3. **Metode 2 (Unggah Gambar):** Klik tombol *"Unggah Foto QR"* jika menggunakan foto barcode yang sudah tersimpan di galeri HP.
4. **Metode 3 (Input Manual):** Masukkan kode berkas atau nomor box secara manual pada kolom yang tersedia, lalu tekan Enter.

### 3.3 Katalog Digital Box Fisik
1. Setiap stiker pada box arsip fisik memiliki QR code unik.
2. Ketika QR box dipindai, pengguna akan diarahkan ke halaman katalog box: `http://web-arsip.test/katalog/box?ruangan=...&rak=...&baris=...&box=...`.
3. Halaman ini menampilkan rekapitulasi seluruh isi dokumen di dalam box tersebut, periode tahun/tanggal berkas, dan status ketersediaan masing-masing berkas.

---

## 4. Panduan Operasional Administrator / Petugas Arsip (Wajib Login)

### 4.1 Dashboard Utama & Visual Monitoring Kapasitas Rak
1. Masuk melalui menu **Login Admin** menggunakan email dan password terdaftar.
2. Di halaman **Dashboard**, Anda akan disajikan:
   - **4 Kartu Ringkasan:** Total Berkas, Berkas Tersedia di Rak, Berkas Dipinjam, dan Peminjaman Terlambat.
   - **Alert Banner Keterlambatan:** Banner merah interaktif jika terdapat arsip yang melewati batas tenggat pengembalian.
   - **Grafik Interaktif:** Perbandingan status arsip (Donut Chart) dan sebaran arsip per ruangan (Bar Chart).
   - **Monitoring Kapasitas & Denah Rak Fisik:**
     - Tab pilihan: *Ruang Arsip Kecil* (kapasitas 4 baris) dan *Ruang Arsip Kepesertaan* (kapasitas 6 baris).
     - Menampilkan indikator persentase keterisian rak (Rak A, B, C, D) lengkap dengan progress bar warna dinamis: Hijau (<50%), Kuning (50-84%), Merah (≥85%).
     - Peta rak bertingkat yang merinci jumlah box dan berkas per baris rak.

### 4.2 Manajemen Berkas Fisik & Digital (CRUD)
1. Buka menu **Daftar Arsip** (`/arsip`).
2. Klik tombol hijau **Tambah Arsip**.
3. Isi formulir:
   - **Kode Berkas:** Kode unik (misal: `KLM-JHT-2024-001`).
   - **Nama Berkas:** Nama berkas/peserta klaim (misal: `Berkas Klaim JHT - PT Kalbe Farma Tbk`).
   - **Tanggal Berkas:** Tanggal penetapan berkas (maksimal hari ini).
   - **Ruangan:** Pilih *Ruang Arsip Kecil* atau *Ruang Arsip Kepesertaan*.
   - **Rak & Baris:** Pilihan nomor baris otomatis menyesuaikan kapasitas maksimal ruangan yang dipilih (validasi dinamis).
   - **Nomor Box:** Nomor boks penyimpanan fisik (misal: `Box 01`).
   - **Unggah Dokumen Digital:** Unggah file PDF atau foto dokumen (format `.pdf`, `.jpg`, `.png`, maksimal 10 MB). File ini tersimpan secara privat dan hanya bisa diunduh oleh Admin.
4. Klik **Simpan Data Arsip**.

### 4.3 Filter Digitalisasi Berkas (Scan PDF vs Fisik)
1. Pada toolbar pencarian halaman **Daftar Arsip**, gunakan filter **Semua Dokumen**:
   - Pilih **Sudah Scan PDF** untuk melihat berkas yang sudah memiliki lampiran digital.
   - Pilih **Hanya Fisik** untuk melihat berkas yang belum memiliki lampiran scan (perlu diprioritaskan untuk scanning).
2. Pada tabel, berkas yang sudah di-scan memiliki tombol klik cepat **PDF Scan** untuk membuka file di tab baru.

### 4.4 Import & Export Data Excel
1. **Import Excel:**
   - Klik tombol **Import Excel** pada toolbar.
   - Unduh template format resmi jika diperlukan.
   - Pilih file Excel (`.xlsx` / `.xls`), lalu klik **Proses Import**.
   - Sistem secara cerdas mampu membaca berbagai format tanggal (`YYYY-MM-DD`, `DD/MM/YYYY`, serial number Excel) tanpa eror.
2. **Export Excel:**
   - Klik tombol **Export Excel** untuk mengunduh rekapitulasi data arsip sesuai filter yang sedang aktif dalam format spreadsheet ber-styling resmi BPJS Ketenagakerjaan.

### 4.5 Cetak Stiker QR Code Berkas & Box
1. **Cetak Label Berkas Terpilih:** Ceklis kotak pada berkas yang diinginkan di tabel arsip, lalu klik tombol **Cetak Terpilih**. Label stiker thermal/kertas siap dicetak lengkap dengan logo dan QR code.
2. **Cetak Semua Label:** Klik tombol **Cetak Semua** untuk mencetak seluruh barcode berkas.
3. **Cetak Stiker Box Fisik:** Melalui menu cetak box untuk menghasilkan label resmi penampang depan box arsip lengkap dengan rentang periode tanggal dokumen di dalamnya.

### 4.6 Sirkulasi Peminjaman & Pengembalian Berkas
1. Buka menu **Peminjaman** (`/peminjaman`).
2. Klik tombol **Catat Peminjaman Baru**:
   - Pilih satu atau beberapa berkas yang berstatus *Tersedia*.
   - Masukkan **Nama Peminjam**, **Unit/Divisi Kerja**, **Tanggal Pinjam**, dan **Tenggat Waktu Pengembalian**.
   - Klik **Simpan Peminjaman**.
3. Status berkas otomatis berubah menjadi **Dipinjam** dan tidak dapat dipinjam ganda oleh staf lain.
4. Saat berkas dikembalikan ke ruang arsip, klik tombol **Kembalikan** pada baris transaksi. Sistem akan meminta konfirmasi SweetAlert2 sebelum memulihkan status arsip kembali menjadi **Tersedia**.

### 4.7 Otomasi Pengingat WhatsApp (Overdue Reminder)
1. Jika transaksi peminjaman melewati batas tenggat waktu, sistem otomatis:
   - Menampilkan badge merah berkedip di menu navigasi.
   - Menempatkan transaksi terlambat di posisi paling atas pada tabel peminjaman.
   - Menampilkan tombol **WA (WhatsApp)** berwarna hijau.
2. Klik tombol **WA** untuk langsung membuka WhatsApp Web / Aplikasi WhatsApp dengan teks pesan pengingat resmi yang sudah terisi otomatis (nama peminjam, divisi, kode berkas, dan tanggal jatuh tempo).

### 4.8 Cetak Berita Acara Peminjaman (BAP) Resmi
1. Pada setiap kartu transaksi di halaman Peminjaman, klik tombol **Cetak BAP (PDF)**.
2. Sistem akan mengunduh lembar resmi A4 Berita Acara Serah Terima Arsip ber-kop BPJS Ketenagakerjaan Kantor Cabang Jakarta Rawamangun dengan nomor BAP resmi dan 3 kolom tanda tangan:
   - **Yang Menerima** (Peminjam)
   - **Yang Menyerahkan** (Petugas Pengelola Arsip)
   - **Mengetahui** (Kepala Bidang / Pjs)

### 4.9 Audit Log Aktivitas & Export Excel
1. Buka menu akun pengguna (pojok kanan atas) -> pilih **Log Aktivitas** (`/log-aktivitas`).
2. Menampilkan catatan rekam jejak audit (*audit trail*) setiap aksi create, update, delete, peminjaman, dan pengembalian berkas, mencakup tanggal, waktu, nama petugas, dan keterangan aktivitas.
3. Klik tombol hijau **Export Excel** untuk mengunduh rekap riwayat aktivitas ke format file Excel.

### 4.10 Pencadangan Basis Data (1-Click Database Backup)
1. Buka menu akun pengguna -> pilih **Backup Database (.sql)**.
2. Browser akan langsung mengunduh file dump SQL terkompresi dengan penamaan timestamp (misal: `backup_arsip_2026-10-21_08-30-00.sql`).
3. Cadangan juga dapat dipicu secara terjadwal melalui terminal dengan perintah:
   ```bash
   php artisan arsip:backup
   ```

---

## 5. Spesifikasi Teknis & Kualitas Sistem

### 5.1 Arsitektur & Teknologi
- **Backend Framework:** Laravel 10 / 11 (PHP 8.2+) dengan arsitektur MVC (*Model-View-Controller*).
- **Frontend & Styling:** Blade Templating Engine, Tailwind CSS, Alpine.js.
- **Visualisasi & Scanner:** Chart.js, Html5-Qrcode Library (NPM Bundle).
- **Exporting Engine:** Maatwebsite Excel 3.1 & Barryvdh DomPDF.
- **Database Engine:** MySQL / MariaDB dengan optimasi Composite Database Indexing pada kolom pencarian dan foreign key.

### 5.2 Pengujian Otomatis (Automated Testing)
Aplikasi telah melalui proses *Quality Assurance* ketat dengan **85 Automated Feature & Unit Tests (234 assertions)** tanpa satupun kegagalan:
```text
PASS  Tests\Unit\ExampleTest
PASS  Tests\Feature\ActivityLogExportTest (2 tests)
PASS  Tests\Feature\ArsipDeletionTest (4 tests)
PASS  Tests\Feature\ArsipDigitalFilterTest (2 tests)
PASS  Tests\Feature\ArsipExportExcelTest (1 test)
PASS  Tests\Feature\ArsipFileDokumenTest (3 tests)
PASS  Tests\Feature\ArsipImportTanggalTest (5 tests)
PASS  Tests\Feature\ArsipQrBoxTest (5 tests)
PASS  Tests\Feature\ArsipQrMassalTest (6 tests)
PASS  Tests\Feature\ArsipTanggalBerkasTest (5 tests)
PASS  Tests\Feature\Auth\... (12 tests)
PASS  Tests\Feature\BackupDatabaseTest (2 tests)
PASS  Tests\Feature\BeritaAcaraPeminjamanTest (2 tests)
PASS  Tests\Feature\DashboardMonitoringTest (2 tests)
PASS  Tests\Feature\ExampleTest (1 test)
PASS  Tests\Feature\KatalogBoxTest (5 tests)
PASS  Tests\Feature\PeminjamanFlowTest (6 tests)
PASS  Tests\Feature\PeminjamanUrutanTerlambatTest (3 tests)
PASS  Tests\Feature\ProfileTest (5 tests)
PASS  Tests\Feature\QrBoxTanggalTest (6 tests)
PASS  Tests\Feature\QrScanTest (1 test)

Total: 85 passed (234 assertions)
```

---
*Dokumen ini disusun untuk keperluan Laporan Magang, Presentasi Instansi, dan Landasan Teknis Skripsi Bidang Teknologi Informasi.*
