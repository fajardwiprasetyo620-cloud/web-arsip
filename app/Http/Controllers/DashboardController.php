<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Satu query untuk tiga angka ringkasan
        $stat = Arsip::selectRaw("COUNT(*) AS total,
            COALESCE(SUM(status = 'Tersedia'), 0) AS tersedia,
            COALESCE(SUM(status = 'Dipinjam'), 0) AS dipinjam")->first();

        $arsipPerRuangan = Arsip::select('ruangan', DB::raw('COUNT(*) AS total'))
            ->groupBy('ruangan')
            ->get();

        return view('dashboard', [
            'totalArsip'          => (int) $stat->total,
            'tersedia'            => (int) $stat->tersedia,
            'dipinjam'            => (int) $stat->dipinjam,
            'labelRuangan'        => $arsipPerRuangan->pluck('ruangan')->toJson(),
            'dataRuangan'         => $arsipPerRuangan->pluck('total')->toJson(),
            'peminjamanTerbaru'   => Peminjaman::with('arsip')->latest()->take(5)->get(),
            'jumlahTerlambat'     => Peminjaman::terlambat()->count(),
            'peminjamanTerlambat' => Peminjaman::with('arsip')->terlambat()->orderBy('tenggat_waktu')->take(5)->get(),
        ]);
    }
}
