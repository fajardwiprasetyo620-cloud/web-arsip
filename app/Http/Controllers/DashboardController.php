<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return $this->index();
    }

    public function index()
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
                        'total_box' => $inBaris->pluck('box')->unique()->count(),
                        'boxes' => $inBaris->pluck('box')->unique()->values()->all(),
                    ];
                }

                $barisTerisi = count(array_filter($barisList, fn ($item) => $item['total_arsip'] > 0));
                $persenTerisi = round(($barisTerisi / $maxBaris) * 100);

                $rakData[$rak] = [
                    'total_arsip' => $arsipRak->count(),
                    'total_box' => $arsipRak->pluck('box')->unique()->count(),
                    'baris_terisi' => $barisTerisi,
                    'max_baris' => $maxBaris,
                    'persen' => $persenTerisi,
                    'detail_baris' => $barisList,
                ];
            }

            $kapasitasRuang[$ruang] = [
                'max_baris' => $maxBaris,
                'total_arsip' => $arsipRuang->count(),
                'total_box' => $arsipRuang->pluck('box')->unique()->count(),
                'raks' => $rakData,
            ];
        }

        return view('dashboard', [
            'totalArsip' => (int) $stat->total,
            'tersedia' => (int) $stat->tersedia,
            'dipinjam' => (int) $stat->dipinjam,
            'labelRuangan' => $arsipPerRuangan->pluck('ruangan')->toJson(),
            'dataRuangan' => $arsipPerRuangan->pluck('total')->toJson(),
            'peminjamanTerbaru' => Peminjaman::with('arsip')->latest()->take(5)->get(),
            'jumlahTerlambat' => Peminjaman::terlambat()->count(),
            'peminjamanTerlambat' => Peminjaman::with('arsip')->terlambat()->orderBy('tenggat_waktu')->take(5)->get(),
            'kapasitasRuang' => $kapasitasRuang,
        ]);
    }

    /**
     * Cetak Laporan Eksekutif Kearsipan (Executive Summary PDF) Landscape A4
     */
    public function executivePdf()
    {
        $totalArsip = Arsip::count();
        $arsipTersedia = Arsip::where('status', 'Tersedia')->count();
        $arsipDipinjam = Arsip::where('status', 'Dipinjam')->count();
        $arsipDigital = Arsip::whereNotNull('file_dokumen')->count();
        $persenDigital = $totalArsip > 0 ? round(($arsipDigital / $totalArsip) * 100, 1) : 0;

        $jumlahTerlambat = Peminjaman::terlambat()->count();
        $terlambatList = Peminjaman::with('arsip')->terlambat()->orderBy('tenggat_waktu')->take(8)->get();

        $peminjamanPerDivisi = Peminjaman::select('divisi', DB::raw('COUNT(*) as total'))
            ->whereNotNull('divisi')
            ->groupBy('divisi')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $kapasitasRuang = [];
        $semuaArsip = Arsip::select('ruangan', 'rak', 'baris', 'box')->get();

        foreach (Arsip::KAPASITAS_BARIS as $ruang => $maxBaris) {
            $arsipRuang = $semuaArsip->where('ruangan', $ruang);
            $raks = ['Rak A', 'Rak B', 'Rak C', 'Rak D'];
            $rakList = [];

            foreach ($raks as $rak) {
                $arsipRak = $arsipRuang->where('rak', $rak);
                $barisTerisi = $arsipRak->pluck('baris')->unique()->count();
                $rakList[] = [
                    'nama' => $rak,
                    'total_arsip' => $arsipRak->count(),
                    'total_box' => $arsipRak->pluck('box')->unique()->count(),
                    'baris_terisi' => $barisTerisi,
                    'max_baris' => $maxBaris,
                    'persen' => round(($barisTerisi / $maxBaris) * 100),
                ];
            }

            $kapasitasRuang[] = [
                'ruangan' => $ruang,
                'total_arsip' => $arsipRuang->count(),
                'total_box' => $arsipRuang->pluck('box')->unique()->count(),
                'raks' => $rakList,
            ];
        }

        $pdf = Pdf::loadView('dashboard.executive-pdf', [
            'totalArsip' => $totalArsip,
            'arsipTersedia' => $arsipTersedia,
            'arsipDipinjam' => $arsipDipinjam,
            'arsipDigital' => $arsipDigital,
            'persenDigital' => $persenDigital,
            'jumlahTerlambat' => $jumlahTerlambat,
            'terlambatList' => $terlambatList,
            'peminjamanPerDivisi' => $peminjamanPerDivisi,
            'kapasitasRuang' => $kapasitasRuang,
            'tanggalCetak' => now()->translatedFormat('d F Y H:i'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Eksekutif-E-Arsip-'.now()->format('Ymd_His').'.pdf');
    }
}
