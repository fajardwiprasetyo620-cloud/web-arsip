<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Halaman PUBLIK (tanpa login) isi satu box arsip. Dituju lewat scan QR Box
 * yang ditempel di box fisik: menampilkan semua berkas yang ada di dalam box itu.
 * Sebuah box dikenali dari gabungan: ruangan + rak + baris + nomor box.
 */
class KatalogBoxController extends Controller
{
    public function show(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'ruangan' => 'required|string|max:100',
            'rak'     => 'required|string|max:50',
            'baris'   => 'required|integer|min:1',
            'box'     => 'required|string|max:50',
        ]);

        // Alamat tidak lengkap/salah -> "tidak ditemukan" (halaman publik, bukan redirect)
        abort_if($validator->fails(), 404);

        $lokasi = $validator->validated();

        // Urut dari tanggal berkas terlama; berkas tanpa tanggal di paling bawah
        $arsips = Arsip::where($lokasi)
            ->orderByRaw('tanggal_berkas IS NULL')
            ->orderBy('tanggal_berkas')
            ->orderBy('kode_berkas')
            ->get();

        abort_if($arsips->isEmpty(), 404);

        $jumlahDipinjam = $arsips->where('status', 'Dipinjam')->count();

        // Rentang tanggal berkas di dalam box (kosong jika belum ada yang diisi)
        $bertanggal = $arsips->whereNotNull('tanggal_berkas');
        $tanggalAwal = $bertanggal->min('tanggal_berkas');
        $tanggalAkhir = $bertanggal->max('tanggal_berkas');

        return view('katalog-box', compact('arsips', 'lokasi', 'jumlahDipinjam', 'tanggalAwal', 'tanggalAkhir'));
    }
}
