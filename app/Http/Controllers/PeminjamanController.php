<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Arsip;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with('arsip')->orderBy('created_at', 'desc');

        // 1. Pencarian teks (Nama Peminjam, Divisi, Nama Berkas, atau Kode Berkas)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peminjam', 'like', '%' . $search . '%')
                  ->orWhere('divisi', 'like', '%' . $search . '%')
                  ->orWhereHas('arsip', function ($qArsip) use ($search) {
                      $qArsip->where('nama_berkas', 'like', '%' . $search . '%')
                             ->orWhere('kode_berkas', 'like', '%' . $search . '%');
                  });
            });
        }

        // 2. Penyaringan berdasarkan Tanggal Pinjam
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->where('tanggal_pinjam', $request->tanggal);
        }

        $peminjamans = $query->get();
        $hariIni = now()->startOfDay();
        $isTerlambat = fn ($peminjaman) => $peminjaman->status === 'Dipinjam'
            && !empty($peminjaman->tenggat_waktu)
            && $hariIni->gt(Carbon::parse($peminjaman->tenggat_waktu)->startOfDay());

        $daftarTerlambat = $peminjamans
            ->filter($isTerlambat)
            ->sortBy(fn ($peminjaman) => Carbon::parse($peminjaman->tenggat_waktu)->startOfDay());
        $daftarDipinjam = $peminjamans
            ->filter(fn ($peminjaman) => $peminjaman->status === 'Dipinjam' && !$isTerlambat($peminjaman))
            ->sortByDesc('created_at');
        $daftarDikembalikan = $peminjamans
            ->where('status', 'Dikembalikan')
            ->sortByDesc('created_at');

        $paginate = function ($items, string $pageName) use ($request) {
            $perHalaman = 10;
            $halaman = LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $items->forPage($halaman, $perHalaman)->values(),
                $items->count(),
                $perHalaman,
                $halaman,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $request->query(),
                    'pageName' => $pageName,
                ]
            );
        };

        $peminjamanTerlambat = $paginate($daftarTerlambat, 'terlambat_page');
        $peminjamanAktif = $paginate($daftarDipinjam, 'dipinjam_page');
        $peminjamanDikembalikan = $paginate($daftarDikembalikan, 'dikembalikan_page');

        return view('peminjaman.index', compact(
            'peminjamanTerlambat',
            'peminjamanAktif',
            'peminjamanDikembalikan'
        ));
    }

    public function create()
    {
        $arsips = Arsip::where('status', 'Tersedia')->orderBy('nama_berkas', 'asc')->get();
        return view('peminjaman.create', compact('arsips'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'arsip_id'       => 'required|array|min:1',
            'arsip_id.*'     => ['integer', Rule::exists('arsips', 'id')->whereNull('deleted_at')],
            'nama_peminjam'  => 'required|string|max:255',
            'divisi'         => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tenggat_waktu'  => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $ids = array_values(array_unique($data['arsip_id']));

        // Semua langkah dalam satu transaksi. Baris arsip dikunci (lockForUpdate)
        // supaya dua admin tidak bisa meminjamkan berkas yang sama secara bersamaan.
        DB::transaction(function () use ($ids, $data) {
            $arsips = Arsip::whereIn('id', $ids)->lockForUpdate()->get();

            $tidakTersedia = $arsips->filter(fn (Arsip $a) => $a->status !== 'Tersedia');

            if ($arsips->count() !== count($ids) || $tidakTersedia->isNotEmpty()) {
                $daftar = $tidakTersedia->isNotEmpty()
                    ? ': ' . $tidakTersedia->pluck('kode_berkas')->implode(', ')
                    : '';

                throw ValidationException::withMessages([
                    'arsip_id' => 'Ada arsip yang sudah tidak tersedia' . $daftar . '. Silakan pilih arsip lain.',
                ]);
            }

            foreach ($arsips as $arsip) {
                Peminjaman::create([
                    'arsip_id'       => $arsip->id,
                    'nama_peminjam'  => $data['nama_peminjam'],
                    'divisi'         => $data['divisi'],
                    'tanggal_pinjam' => $data['tanggal_pinjam'],
                    'tenggat_waktu'  => $data['tenggat_waktu'],
                    'status'         => 'Dipinjam',
                ]);

                $arsip->update(['status' => 'Dipinjam']);
            }

            ActivityLog::catat('PINJAM', 'mencatat peminjaman ' . $arsips->count() . ' arsip ('
                . $arsips->pluck('kode_berkas')->implode(', ') . ') oleh '
                . $data['nama_peminjam'] . ' - ' . $data['divisi'] . '.');
        });

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman arsip berhasil dicatat!');
    }

    /**
     * Proses pengembalian arsip.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $sudahDikembalikan = false;

        DB::transaction(function () use ($peminjaman, &$sudahDikembalikan) {
            // Kunci baris agar pengembalian tidak terproses dua kali
            $pinjam = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);

            if ($pinjam->status === 'Dikembalikan') {
                $sudahDikembalikan = true;
                return;
            }

            $pinjam->update([
                'status'          => 'Dikembalikan',
                'tanggal_kembali' => now(),
                'disetujui_oleh'  => auth()->user()->name,
            ]);

            // withTrashed: tetap aman walau arsipnya sudah masuk riwayat hapus
            $arsip = Arsip::withTrashed()->find($pinjam->arsip_id);
            $arsip?->update(['status' => 'Tersedia']);

            ActivityLog::catat('KEMBALI', 'memproses pengembalian arsip '
                . ($arsip?->kode_berkas ?? '#' . $pinjam->arsip_id) . ' dari ' . $pinjam->nama_peminjam . '.');
        });

        if ($sudahDikembalikan) {
            return redirect()->back()->with('error', 'Arsip ini sudah tercatat dikembalikan sebelumnya.');
        }

        return redirect()->back()->with('success', 'Arsip berhasil dikembalikan dan dicatat oleh sistem.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // Riwayat yang masih berstatus Dipinjam tidak boleh dihapus,
        // kalau tidak status arsipnya akan tertahan "Dipinjam" selamanya.
        if ($peminjaman->status === 'Dipinjam') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Peminjaman ini masih berstatus Dipinjam. Proses pengembaliannya terlebih dahulu.');
        }

        $kode = $peminjaman->arsip?->kode_berkas ?? '#' . $peminjaman->arsip_id;
        $nama = $peminjaman->nama_peminjam;

        $peminjaman->delete();

        ActivityLog::catat('HAPUS PEMINJAMAN', 'menghapus riwayat peminjaman arsip ' . $kode . ' oleh ' . $nama . '.');

        return redirect()->route('peminjaman.index')->with('success', 'Riwayat peminjaman berhasil dihapus!');
    }

    public function exportPdf()
    {
        $peminjamans = Peminjaman::with('arsip')->orderBy('tanggal_pinjam', 'desc')->get();

        $pdf = Pdf::loadView('peminjaman.pdf', compact('peminjamans'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Rekap_Peminjaman_Arsip.pdf');
    }

    public function exportPdfSelected(Request $request)
    {
        if (!$request->has('peminjaman_ids') || empty($request->peminjaman_ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu data untuk dicetak!');
        }

        $peminjamans = Peminjaman::with('arsip')
                        ->whereIn('id', $request->peminjaman_ids)
                        ->orderBy('tanggal_pinjam', 'desc')
                        ->get();

        $pdf = Pdf::loadView('peminjaman.pdf', compact('peminjamans'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Peminjaman_Terpilih.pdf');
    }

    /**
     * Cetak Lembar Berita Acara Peminjaman (BAP) resmi per transaksi dengan kolom tanda tangan.
     */
    public function beritaAcara(Request $request)
    {
        $nama = $request->query('nama');
        $tanggal = $request->query('tanggal');
        $id = $request->query('id');

        $query = Peminjaman::with('arsip');
        if ($id) {
            $peminjamans = $query->where('id', $id)->get();
        } elseif ($nama && $tanggal) {
            $peminjamans = $query->where('nama_peminjam', $nama)
                                 ->whereDate('tanggal_pinjam', $tanggal)
                                 ->get();
        } else {
            abort(404, 'Parameter Berita Acara tidak lengkap.');
        }

        if ($peminjamans->isEmpty()) {
            abort(404, 'Data peminjaman tidak ditemukan.');
        }

        $first = $peminjamans->first();
        $nomorBap = 'BAP/ARSIP/' . \Carbon\Carbon::parse($first->tanggal_pinjam)->format('Ymd') . '/' . str_pad($first->id, 4, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('peminjaman.bap', compact('peminjamans', 'first', 'nomorBap'));
        $pdf->setPaper('A4', 'portrait');

        $slugName = \Illuminate\Support\Str::slug($first->nama_peminjam . '-' . $first->tanggal_pinjam);
        return $pdf->stream("Berita_Acara_Peminjaman_{$slugName}.pdf");
    }
}
