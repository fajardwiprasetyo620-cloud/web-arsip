# Perbaikan E-Arsip - Tahap 1 (Keamanan) dan Tahap 2 (Logika Peminjaman)

## Cara memasang
1. Backup dulu folder project dan database.
2. Ekstrak zip ini ke folder project. File dengan path yang sama akan ditimpa.
3. Pindahkan file dokumen lama ke penyimpanan privat:
       php artisan arsip:pindah-file
4. Bersihkan cache:
       php artisan optimize:clear
5. (Opsional, disarankan) Hapus dua file yang sudah tidak terpakai:
   - app/Http/Controllers/Auth/RegisteredUserController.php
   - resources/views/auth/register.blade.php
6. Untuk menambah admin baru (registrasi publik sudah dimatikan), pakai tinker:
       php artisan tinker
       App\Models\User::create(['name'=>'Nama Admin','email'=>'admin@contoh.com','password'=>bcrypt('PasswordKuat123!')]);

## Daftar file
| File | Perubahan |
|---|---|
| routes/auth.php | Route register dihapus |
| routes/web.php | Tambah route `arsip.file`, rapikan komentar |
| app/Http/Requests/ArsipRequest.php | BARU - validasi bersama untuk store dan update |
| app/Http/Controllers/ArsipController.php | Validasi via ArsipRequest, file dokumen privat, ganti file di edit, blok hapus arsip yang dipinjam, hapus massal aman |
| app/Http/Controllers/PeminjamanController.php | Transaksi + lock saat pinjam, cegah kembali dua kali, cegah hapus peminjaman aktif, log aktivitas |
| app/Models/Arsip.php | Konstanta kapasitas baris, helper sedangDipinjam() |
| app/Models/ActivityLog.php | Helper ActivityLog::catat() |
| app/Console/Commands/PindahkanFileArsip.php | BARU - memindahkan file lama ke disk privat |
| resources/views/components/flash.blade.php | BARU - pesan sukses/peringatan/error + error validasi |
| resources/views/arsip/index.blade.php | Tampilkan pesan, link file lewat route privat |
| resources/views/arsip/create.blade.php | Tampilkan pesan dan error validasi |
| resources/views/arsip/edit.blade.php | Bisa mengganti file dokumen, tampilkan error |
| resources/views/arsip/history.blade.php | Tampilkan pesan |

## Catatan
- Halaman arsip sebelumnya tidak menampilkan pesan sukses/error sama sekali. Sekarang ada lewat <x-flash />.
- Kode berkas yang sudah ada di Riwayat Hapus tetap dianggap duplikat (sesuai unique index database).
- Arsip hasil import dengan ruangan "Belum Diatur" harus dipilihkan ruangan yang valid saat diedit.
- Jangan sertakan file .env saat membagikan project. Ganti password database jika sudah pernah terbagikan.

## Cek setelah pasang
- [ ] /register menampilkan 404
- [ ] Buka link file dokumen saat login: tampil. Saat logout: diarahkan ke login
- [ ] Tambah arsip dengan file, lalu edit dan ganti file: file lama terhapus
- [ ] Coba hapus arsip berstatus Dipinjam: ditolak dengan pesan
- [ ] Pinjam arsip, lalu klik kembalikan dua kali: kali kedua ditolak
- [ ] Menu Log Aktivitas mencatat PINJAM, KEMBALI, TAMBAH, EDIT, HAPUS
