<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;

/**
 * Halaman katalog PUBLIK (tanpa login) untuk karyawan mencari lokasi fisik arsip.
 */
class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $arsips = Arsip::filter($request->only('search', 'ruangan', 'status'))
            ->orderBy('kode_berkas')
            ->paginate(15)
            ->withQueryString();

        $ruangans = Arsip::select('ruangan')->distinct()->pluck('ruangan');

        return view('katalog', compact('arsips', 'ruangans'));
    }

    public function show(string $id)
    {
        $arsip = Arsip::findOrFail($id);

        return view('katalog-detail', compact('arsip'));
    }

    public function scan()
    {
        return view('scan-qr');
    }
}
