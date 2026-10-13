<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Arsip;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

        // Kelompokkan per transaksi (satu peminjam pada satu tanggal pinjam bisa
        // meminjam beberapa arsip sekaligus), lalu paginate per TRANSAKSI -- bukan
        // per baris arsip -- supaya satu transaksi tidak terpotong di tengah halaman.
        $semuaTransaksi = $peminjamans->groupBy(function ($item) {
            return $item->nama_peminjam . '#' . $item->tanggal_pinjam;
        });

        $perHalaman = 10;
        $halaman = LengthAwarePaginator::resolveCurrentPage();

        $groupedPeminjamans = new LengthAwarePaginator(
            $semuaTransaksi->forPage($halaman, $perHalaman),
            $semuaTransaksi->count(),
            $perHalaman,
            $halaman,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('peminjaman.index', compact('groupedPeminjamans'));
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
}
