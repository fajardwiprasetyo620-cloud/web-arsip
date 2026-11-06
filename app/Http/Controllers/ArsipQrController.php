<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;

/**
 * Cetak banyak stiker QR Code sekaligus (satu lembar berisi banyak label),
 * untuk ditempel pada box arsip fisik.
 */
class ArsipQrController extends Controller
{
    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'integer',
        ], [
            'ids.required' => 'Pilih minimal satu arsip untuk dicetak QR Code-nya.',
            'ids.min' => 'Pilih minimal satu arsip untuk dicetak QR Code-nya.',
            'ids.max' => 'Maksimal 100 QR Code dalam sekali cetak.',
        ]);

        $arsips = Arsip::whereIn('id', $request->ids)
            ->orderBy('kode_berkas')
            ->get();

        if ($arsips->isEmpty()) {
            return redirect()->route('arsip.index')
                ->with('error', 'Arsip yang dipilih tidak ditemukan.');
        }

        return view('arsip.qrcode-massal', compact('arsips'));
    }

    /**
     * Cetak stiker QR per BOX (bukan per berkas). Satu box = satu stiker, dan QR-nya
     * membuka daftar semua berkas di dalam box itu.
     *
     * Input: ids[] (arsip yang dicentang; box-nya diambil tanpa duplikat)
     *        atau semua=1 (satu stiker untuk setiap box yang ada).
     */
    public function box(Request $request)
    {
        $request->validate([
            'ids' => 'required_without:semua|array|min:1|max:200',
            'ids.*' => 'integer',
            'semua' => 'nullable|boolean',
        ], [
            'ids.required_without' => 'Pilih minimal satu arsip, atau gunakan tombol "QR Semua Box".',
            'ids.min' => 'Pilih minimal satu arsip, atau gunakan tombol "QR Semua Box".',
            'ids.max' => 'Maksimal 200 arsip dipilih sekali cetak.',
        ]);

        // Satu baris per box, lengkap dengan jumlah berkas (dihitung dari seluruh isi box)
        $semuaBox = Arsip::query()
            ->select('ruangan', 'rak', 'baris', 'box')
            ->selectRaw('COUNT(*) as jumlah')
            ->selectRaw("SUM(CASE WHEN status = 'Dipinjam' THEN 1 ELSE 0 END) as dipinjam")
            ->selectRaw('MIN(tanggal_berkas) as tanggal_awal')
            ->selectRaw('MAX(tanggal_berkas) as tanggal_akhir')
            ->groupBy('ruangan', 'rak', 'baris', 'box')
            ->orderBy('ruangan')->orderBy('rak')->orderBy('baris')->orderBy('box')
            ->get();

        if (! $request->boolean('semua')) {
            $terpilih = Arsip::whereIn('id', $request->ids)
                ->get(['ruangan', 'rak', 'baris', 'box'])
                ->map(fn ($a) => $this->kunciBox($a))
                ->flip();

            $semuaBox = $semuaBox
                ->filter(fn ($b) => $terpilih->has($this->kunciBox($b)))
                ->values();
        }

        if ($semuaBox->isEmpty()) {
            return redirect()->route('arsip.index')->with('error', 'Box tidak ditemukan.');
        }

        if ($semuaBox->count() > 200) {
            return redirect()->route('arsip.index')
                ->with('error', 'Jumlah box terlalu banyak (maksimal 200 sekali cetak). Pilih sebagian saja.');
        }

        return view('arsip.qrcode-box', ['boxes' => $semuaBox]);
    }

    private function kunciBox($arsip): string
    {
        return implode('|', [$arsip->ruangan, $arsip->rak, (string) $arsip->baris, $arsip->box]);
    }
}
