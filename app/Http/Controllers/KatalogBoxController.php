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
            'rak' => 'required|string|max:50',
            'baris' => 'nullable|integer|min:1',
            'box' => 'required|string|max:50',
        ]);

        // Alamat tidak lengkap/salah -> "tidak ditemukan" (halaman publik, bukan redirect)
        abort_if($validator->fails(), 404);

        $lokasi = array_filter($validator->validated(), fn ($v) => $v !== null && $v !== '');

        // Query berkas dalam box dengan toleransi variasi format penamaan box ("03", "3", "Box 03", dsb.)
        $arsips = $this->queryArsips($lokasi);

        abort_if($arsips->isEmpty(), 404);

        // Jika baris awalnya tidak disertakan di URL tetapi semua berkas berada pada satu baris, tampilkan barisnya
        if (!isset($lokasi['baris']) && $arsips->isNotEmpty()) {
            $lokasi['baris'] = $arsips->first()->baris;
        }

        $jumlahDipinjam = $arsips->where('status', 'Dipinjam')->count();

        // Rentang tanggal berkas di dalam box (kosong jika belum ada yang diisi)
        $bertanggal = $arsips->whereNotNull('tanggal_berkas');
        $tanggalAwal = $bertanggal->min('tanggal_berkas');
        $tanggalAkhir = $bertanggal->max('tanggal_berkas');

        return view('katalog-box', compact('arsips', 'lokasi', 'jumlahDipinjam', 'tanggalAwal', 'tanggalAkhir'));
    }

    /**
     * Cari berkas dengan fleksibilitas pencarian nomor box (dengan atau tanpa prefiks 'Box').
     */
    private function queryArsips(array &$lokasi)
    {
        $buildQuery = fn (array $filter) => Arsip::where($filter)
            ->orderByRaw('tanggal_berkas IS NULL')
            ->orderBy('tanggal_berkas')
            ->orderBy('kode_berkas');

        // 1. Coba cari dengan nilai apa adanya
        $res = $buildQuery($lokasi)->get();
        if ($res->isNotEmpty()) {
            return $res;
        }

        // 2. Coba variasi format nama box (misal input '03' dicari 'Box 03', atau 'Box 03' dicari '03')
        $boxInput = trim((string) ($lokasi['box'] ?? ''));
        $kandidatBox = [];

        if (preg_match('/^box\s*(.*)$/i', $boxInput, $m)) {
            $rawNumber = trim($m[1]);
            $kandidatBox[] = $rawNumber;
            if (is_numeric($rawNumber)) {
                $kandidatBox[] = (string) (int) $rawNumber;
                $kandidatBox[] = sprintf('%02d', (int) $rawNumber);
            }
        } else {
            $kandidatBox[] = 'Box ' . $boxInput;
            if (is_numeric($boxInput)) {
                $kandidatBox[] = 'Box ' . sprintf('%02d', (int) $boxInput);
                $kandidatBox[] = sprintf('%02d', (int) $boxInput);
            }
        }

        foreach (array_unique($kandidatBox) as $boxAlt) {
            $altLokasi = $lokasi;
            $altLokasi['box'] = $boxAlt;
            $resAlt = $buildQuery($altLokasi)->get();
            if ($resAlt->isNotEmpty()) {
                $lokasi['box'] = $boxAlt;
                return $resAlt;
            }
        }

        return $res;
    }
}
