<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ArsipExportController;
use App\Http\Controllers\ArsipImportController;
use App\Http\Controllers\ArsipQrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogBoxController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN PUBLIK (Tanpa Login) - Beranda web untuk pencarian karyawan
Route::get('/', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');
Route::get('/box', [KatalogBoxController::class, 'show'])->name('katalog.box');
Route::get('/scan-qr', [KatalogController::class, 'scan'])->name('qr.scan');

// 2. HALAMAN ADMIN (Wajib Login)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/executive-pdf', [DashboardController::class, 'executivePdf'])->name('dashboard.executivePdf');

    // Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Kustom Arsip
    Route::get('/arsip/quick-search', [ArsipController::class, 'quickSearch'])->name('arsip.quickSearch');
    Route::post('/arsip/import', [ArsipImportController::class, 'import'])->name('arsip.import');
    Route::get('/arsip/template-excel', [ArsipImportController::class, 'template'])->name('arsip.template');
    Route::get('/arsip/riwayat/hapus', [ArsipController::class, 'history'])->name('arsip.history');
    Route::post('/arsip/riwayat/{id}/restore', [ArsipController::class, 'restore'])->name('arsip.restore');
    Route::delete('/arsip/riwayat/{id}/force', [ArsipController::class, 'forceDelete'])->name('arsip.forceDelete');

    // Rute Export PDF & Excel Arsip
    Route::get('/export/pdf-arsip', [ArsipExportController::class, 'pdf'])->name('arsip.pdf');
    Route::post('/export/pdf-arsip-selected', [ArsipExportController::class, 'pdfSelected'])->name('arsip.pdf.selected');
    Route::get('/export/excel-arsip', [ArsipExportController::class, 'excel'])->name('arsip.excel');

    // Rute Hapus Masal
    Route::post('/arsip/bulk-delete', [ArsipController::class, 'bulkDelete'])->name('arsip.bulkDelete');

    // Rute QR Code
    Route::get('/arsip/{arsip}/qrcode', [ArsipController::class, 'printQr'])->name('arsip.qrcode');

    // Cetak banyak QR Code sekaligus (GET: halaman cetak, aman dimuat ulang).
    // Harus di atas Route::resource('arsip') supaya tidak dianggap arsip/{arsip}.
    Route::get('/arsip/qrcode-massal', [ArsipQrController::class, 'bulk'])->name('arsip.qrcode.bulk');

    // Cetak QR per BOX (satu stiker = satu box, isinya semua berkas di box itu)
    Route::get('/arsip/qrcode-box', [ArsipQrController::class, 'box'])->name('arsip.qrcode.box');

    // Rute file dokumen digital (privat, hanya untuk admin yang login)
    Route::get('/arsip/{arsip}/file', [ArsipController::class, 'file'])->name('arsip.file');

    // Rute Resource Arsip Utama
    Route::resource('arsip', ArsipController::class);

    // Rute Cetak Laporan PDF Peminjaman & Berita Acara (BAP)
    Route::get('/peminjaman/berita-acara', [PeminjamanController::class, 'beritaAcara'])->name('peminjaman.bap');
    Route::get('/peminjaman-export-pdf', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.pdf');
    Route::post('/peminjaman-export-pdf-selected', [PeminjamanController::class, 'exportPdfSelected'])->name('peminjaman.pdf.selected');

    // Rute Resource Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('arsip.logs');
    Route::get('/log-aktivitas/excel', [LogAktivitasController::class, 'excel'])->name('arsip.logs.excel');

    // Rute Backup Database (Admin Only)
    Route::get('/database/backup', [\App\Http\Controllers\BackupController::class, 'download'])->name('database.backup');
});

require __DIR__.'/auth.php';
