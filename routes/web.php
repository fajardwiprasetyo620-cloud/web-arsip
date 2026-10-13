<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\ArsipExportController;
use App\Http\Controllers\ArsipImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. HALAMAN PUBLIK (Tanpa Login) - Beranda web untuk pencarian karyawan
Route::get('/', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

// 2. HALAMAN ADMIN (Wajib Login)
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Kustom Arsip
    Route::post('/arsip/import', [ArsipImportController::class, 'import'])->name('arsip.import');
    Route::get('/arsip/template-excel', [ArsipImportController::class, 'template'])->name('arsip.template');
    Route::get('/arsip/riwayat/hapus', [ArsipController::class, 'history'])->name('arsip.history');
    Route::post('/arsip/riwayat/{id}/restore', [ArsipController::class, 'restore'])->name('arsip.restore');

    // Rute Export PDF Arsip
    Route::get('/export/pdf-arsip', [ArsipExportController::class, 'pdf'])->name('arsip.pdf');
    Route::post('/export/pdf-arsip-selected', [ArsipExportController::class, 'pdfSelected'])->name('arsip.pdf.selected');

    // Rute Hapus Masal
    Route::post('/arsip/bulk-delete', [ArsipController::class, 'bulkDelete'])->name('arsip.bulkDelete');

    // Rute QR Code
    Route::get('/arsip/{id}/qrcode', [ArsipController::class, 'printQr'])->name('arsip.qrcode');

    // Rute file dokumen digital (privat, hanya untuk admin yang login)
    Route::get('/arsip/{arsip}/file', [ArsipController::class, 'file'])->name('arsip.file');

    // Rute Resource Arsip Utama
    Route::resource('arsip', ArsipController::class);

    // Rute Cetak Laporan PDF Peminjaman
    Route::get('/peminjaman-export-pdf', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.pdf');
    Route::post('/peminjaman-export-pdf-selected', [PeminjamanController::class, 'exportPdfSelected'])->name('peminjaman.pdf.selected');

    // Rute Resource Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('arsip.logs');
});

require __DIR__.'/auth.php';
