<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArsipRequest;
use App\Models\ActivityLog;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * CRUD arsip untuk halaman admin, beserta file dokumen privat, riwayat hapus,
 * hapus massal, dan cetak QR code.
 *
 * Fungsi lain yang dulu ada di sini sekarang dipisah:
 * - Katalog publik        -> KatalogController
 * - Import Excel          -> ArsipImportController
 * - Export PDF            -> ArsipExportController
 * - Log aktivitas         -> LogAktivitasController
 */
class ArsipController extends Controller
{
    /** File dokumen disimpan di disk privat (storage/app/arsip_digital), bukan di folder publik. */
    private const DISK = 'local';
    private const DIR  = 'arsip_digital';

    public function index(Request $request)
    {
        $arsips = Arsip::filter($request->only('search', 'ruangan', 'status'))
            ->orderBy('kode_berkas')
            ->paginate(15)
            ->withQueryString();
        $ruangans = Arsip::select('ruangan')->distinct()->pluck('ruangan');

        return view('arsip.index', compact('arsips', 'ruangans'));
    }

    public function create()
    {
        return view('arsip.create');
    }

    public function store(ArsipRequest $request)
    {
        // Hanya data yang lolos validasi yang dipakai. Status selalu dimulai "Tersedia".
        $data = $request->safe()->except('file_dokumen');
        $data['status'] = 'Tersedia';

        if ($request->hasFile('file_dokumen')) {
            $data['file_dokumen'] = $this->simpanFile($request->file('file_dokumen'), $data['kode_berkas']);
        }

        $arsip = Arsip::create($data);

        ActivityLog::catat('TAMBAH', 'menambahkan arsip baru: ' . $arsip->kode_berkas);

        return redirect()->route('arsip.index')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Arsip $arsip)
    {
        return view('arsip.edit', compact('arsip'));
    }

    public function update(ArsipRequest $request, Arsip $arsip)
    {
        // 1. Isi model dengan data tervalidasi TANPA menyimpan dulu
        $arsip->fill($request->safe()->except('file_dokumen'));

        // 2. Deteksi perubahan lokasi
        $perubahan = [];
        $label = ['ruangan' => 'Ruangan', 'rak' => 'Rak', 'baris' => 'Baris', 'box' => 'Box'];
        foreach ($label as $kolom => $nama) {
            if ($arsip->isDirty($kolom)) {
                $perubahan[] = $nama . ' (' . $arsip->getOriginal($kolom) . ' ➔ ' . $arsip->{$kolom} . ')';
            }
        }

        // 3. Ganti file dokumen jika ada unggahan baru
        $fileLama = null;
        if ($request->hasFile('file_dokumen')) {
            $fileLama = $arsip->file_dokumen;
            $arsip->file_dokumen = $this->simpanFile($request->file('file_dokumen'), $arsip->kode_berkas);
            $perubahan[] = 'File dokumen digital';
        }

        $detailPerubahan = !empty($perubahan) ? implode(', ', $perubahan) : 'data umum/nama berkas';

        // 4. Simpan, lalu hapus file lama (setelah data baru aman tersimpan)
        $arsip->save();
        if ($fileLama) {
            $this->hapusFile($fileLama);
        }

        ActivityLog::catat('EDIT', 'mengubah ' . $detailPerubahan . ' pada arsip ' . $arsip->kode_berkas);

        return redirect()->route('arsip.index')->with('success', 'Data Berhasil Diperbarui!');
    }

    public function destroy(Arsip $arsip)
    {
        if ($arsip->sedangDipinjam()) {
            return redirect()->route('arsip.index')
                ->with('error', 'Arsip ' . $arsip->kode_berkas . ' sedang dipinjam. Proses pengembaliannya dulu sebelum dihapus.');
        }

        $kode = $arsip->kode_berkas;
        $arsip->delete();

        ActivityLog::catat('HAPUS', 'memindahkan arsip ' . $kode . ' ke riwayat hapus.');

        return redirect()->route('arsip.index')->with('success', 'Data Berhasil Dihapus!');
    }

    /**
     * Menyajikan file dokumen digital. Hanya bisa diakses setelah login,
     * karena file disimpan di penyimpanan privat.
     */
    public function file(Arsip $arsip)
    {
        $relativePath = self::DIR . '/' . $arsip->file_dokumen;

        abort_unless($arsip->file_dokumen && Storage::disk(self::DISK)->exists($relativePath), 404);

        return response()->file(storage_path('app/' . $relativePath), [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function history()
    {
        $arsips = Arsip::onlyTrashed()->get();
        return view('arsip.history', compact('arsips'));
    }

    public function restore(string $id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $arsip->restore();

        ActivityLog::catat('PULIHKAN', 'memulihkan arsip ' . $arsip->kode_berkas . ' dari riwayat hapus.');

        return redirect()->route('arsip.history')->with('success', 'Arsip berhasil dipulihkan!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ], [
            'ids.required' => 'Tidak ada data yang dipilih untuk dihapus.',
            'ids.min'      => 'Tidak ada data yang dipilih untuk dihapus.',
        ]);

        $arsips = Arsip::whereIn('id', $request->ids)->get();

        // Arsip yang sedang dipinjam tidak boleh dihapus
        [$dipinjam, $bisaDihapus] = $arsips->partition(fn (Arsip $a) => $a->sedangDipinjam());

        if ($bisaDihapus->isEmpty()) {
            return redirect()->route('arsip.index')
                ->with('error', 'Semua arsip yang dipilih sedang dipinjam, sehingga tidak ada yang dihapus.');
        }

        Arsip::whereIn('id', $bisaDihapus->pluck('id'))->delete();

        ActivityLog::catat('HAPUS MASSAL', 'menghapus ' . $bisaDihapus->count() . ' arsip sekaligus.');

        $redirect = redirect()->route('arsip.index')
            ->with('success', $bisaDihapus->count() . ' Arsip berhasil dihapus sekaligus!');

        if ($dipinjam->isNotEmpty()) {
            $redirect->with('warning', $dipinjam->count() . ' arsip dilewati karena sedang dipinjam: '
                . $dipinjam->pluck('kode_berkas')->implode(', ') . '.');
        }

        return $redirect;
    }

    public function printQr(string $id)
    {
        $arsip = Arsip::findOrFail($id);
        $url = route('katalog.show', $arsip->id);
        return view('arsip.qrcode', compact('arsip', 'url'));
    }

    // =========================================================================
    // HELPER FILE DOKUMEN
    // =========================================================================
    private function simpanFile(UploadedFile $file, string $kode): string
    {
        // Akhiran acak agar nama file tidak bisa ditebak
        $nama = Str::slug($kode) . '_' . Str::random(12) . '.' . $file->extension();
        $file->storeAs(self::DIR, $nama, self::DISK);

        return $nama;
    }

    private function hapusFile(string $nama): void
    {
        Storage::disk(self::DISK)->delete(self::DIR . '/' . $nama);
        Storage::disk('public')->delete(self::DIR . '/' . $nama); // sisa file lama, jika ada
    }
}
