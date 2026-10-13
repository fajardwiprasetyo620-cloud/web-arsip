# Menampilkan "Disetujui Oleh" pada Pengembalian Arsip

## Kenapa
Sejak Tahap 2, setiap kali arsip dikembalikan, sistem sudah otomatis mencatat
nama admin yang memprosesnya ke kolom `disetujui_oleh` di database. Tapi data
ini belum pernah ditampilkan di layar maupun di laporan PDF, jadi admin lain
tidak bisa langsung tahu siapa yang meng-acc suatu pengembalian tanpa membuka
menu Log Aktivitas.

## File yang berubah
| File | Perubahan |
|---|---|
| resources/views/peminjaman/index.blade.php | Menambahkan "· oleh: Nama Admin" di baris "Dikembalikan pd: ..." |
| resources/views/peminjaman/pdf.blade.php | Menambahkan kolom baru "Diacc Oleh" pada tabel laporan PDF |

Tidak ada perubahan pada Controller atau Model -- datanya memang sudah
tersimpan sejak Tahap 2, cuma ditampilkan.

## Cara pasang
1. Ekstrak zip ke folder project, pilih Replace.
2. Tidak perlu menjalankan perintah apa pun setelahnya (murni file tampilan).

## Cara mengecek
1. Buka menu Peminjaman, cari salah satu riwayat yang berstatus "Dikembalikan".
   Sekarang harus ada tulisan "· oleh: <nama admin>" di bawah tanggal kembali.
2. Klik export PDF laporan peminjaman. Tabel PDF sekarang punya kolom "Diacc Oleh".
3. Catatan: peminjaman yang SUDAH dikembalikan SEBELUM perbaikan Tahap 2 dipasang
   tidak akan punya nama admin (tertulis "-" di PDF, atau tidak ada baris "oleh:"
   di layar), karena kolomnya memang belum terisi saat itu. Ini normal, bukan bug --
   hanya pengembalian yang diproses SETELAH Tahap 2 yang tercatat.
