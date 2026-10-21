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

        // Data kapasitas ruang & rak fisik (1 query in-memory collection)
        $kapasitasRuang = [];
        $semuaArsip = Arsip::select('ruangan', 'rak', 'baris', 'box')->get();

        foreach (Arsip::KAPASITAS_BARIS as $ruang => $maxBaris) {
            $arsipRuang = $semuaArsip->where('ruangan', $ruang);
            $raks = ['Rak A', 'Rak B', 'Rak C', 'Rak D'];
            $rakData = [];

            foreach ($raks as $rak) {
                $arsipRak = $arsipRuang->where('rak', $rak);
                $barisList = [];

                for ($b = 1; $b <= $maxBaris; $b++) {
                    $inBaris = $arsipRak->where('baris', $b);
                    $barisList[$b] = [
                        'total_arsip' => $inBaris->count(),
                        'total_box'   => $inBaris->pluck('box')->unique()->count(),
                        'boxes'       => $inBaris->pluck('box')->unique()->values()->all(),
                    ];
                }

                $barisTerisi = count(array_filter($barisList, fn ($item) => $item['total_arsip'] > 0));
                $persenTerisi = round(($barisTerisi / $maxBaris) * 100);

                $rakData[$rak] = [
                    'total_arsip'  => $arsipRak->count(),
                    'total_box'    => $arsipRak->pluck('box')->unique()->count(),
                    'baris_terisi' => $barisTerisi,
                    'max_baris'    => $maxBaris,
                    'persen'       => $persenTerisi,
                    'detail_baris' => $barisList,
                ];
            }

            $kapasitasRuang[$ruang] = [
                'max_baris'   => $maxBaris,
                'total_arsip' => $arsipRuang->count(),
                'total_box'   => $arsipRuang->pluck('box')->unique()->count(),
                'raks'        => $rakData,
            ];
        }

        return view('dashboard', [
            'totalArsip'          => (int) $stat->total,
            'tersedia'            => (int) $stat->tersedia,
            'dipinjam'            => (int) $stat->dipinjam,
            'labelRuangan'        => $arsipPerRuangan->pluck('ruangan')->toJson(),
            'dataRuangan'         => $arsipPerRuangan->pluck('total')->toJson(),
            'peminjamanTerbaru'   => Peminjaman::with('arsip')->latest()->take(5)->get(),
            'jumlahTerlambat'     => Peminjaman::terlambat()->count(),
            'peminjamanTerlambat' => Peminjaman::with('arsip')->terlambat()->orderBy('tenggat_waktu')->take(5)->get(),
            'kapasitasRuang'      => $kapasitasRuang,
        ]);
    }
}
