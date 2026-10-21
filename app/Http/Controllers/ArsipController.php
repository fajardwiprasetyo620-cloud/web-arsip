<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArsipRequest;
use App\Models\ActivityLog;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
        $arsips = Arsip::filter($request->only('search', 'ruangan', 'status', 'digital'))
            ->orderBy('kode_berkas')
            ->paginate(15)
            ->withQueryString();

        // Gabungkan daftar ruangan standar dengan yang ada di database agar opsi selalu lengkap
        $ruanganDariDb = Arsip::whereNotNull('ruangan')->distinct()->pluck('ruangan')->toArray();
        $ruangans = collect(array_keys(Arsip::KAPASITAS_BARIS))
            ->merge($ruanganDariDb)
            ->filter()
            ->unique()
            ->values();

        return view('arsip.index', compact('arsips', 'ruangans'));
    }

    public function create()
    {
        return view('arsip.create');
    }

    public function store(ArsipRequest $request)
    {
        // Hanya data yang lolos validasi yang dipakai. Status selalu dimulai "Tersedia".
        $data = $request->safe()->except(['file_dokumen', 'hapus_file']);
        $data['status'] = 'Tersedia';

        $namaFileBaru = null;
        if ($request->hasFile('file_dokumen')) {
            $namaFileBaru = $this->simpanFile($request->file('file_dokumen'), $data['kode_berkas']);
            $data['file_dokumen'] = $namaFileBaru;
        }

        try {
            $arsip = DB::transaction(function () use ($data) {
                $baru = Arsip::create($data);
                ActivityLog::catat('TAMBAH', 'menambahkan arsip baru: ' . $baru->kode_berkas);
                return $baru;
            });
        } catch (\Throwable $e) {
            // Hapus file baru jika penyimpanan data ke database gagal
            if ($namaFileBaru) {
                $this->hapusFile($namaFileBaru);
            }
            throw $e;
        }

        return redirect()->route('arsip.index')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit(Arsip $arsip)
    {
        return view('arsip.edit', compact('arsip'));
    }

    public function update(ArsipRequest $request, Arsip $arsip)
    {
        // 1. Isi model dengan data tervalidasi TANPA menyimpan dulu
        $arsip->fill($request->safe()->except(['file_dokumen', 'hapus_file']));

        // 2. Deteksi perubahan kolom
        $perubahan = [];
        $label = [
            'ruangan'        => 'Ruangan',
            'rak'            => 'Rak',
            'baris'          => 'Baris',
            'box'            => 'Box',
            'kode_berkas'    => 'Kode Berkas',
            'nama_berkas'    => 'Nama Berkas',
            'tanggal_berkas' => 'Tanggal Berkas',
        ];
        foreach ($label as $kolom => $nama) {
            if ($arsip->isDirty($kolom)) {
                $perubahan[] = $nama . ' (' . ($arsip->getOriginal($kolom) ?? '-') . ' ➔ ' . ($arsip->{$kolom} ?? '-') . ')';
            }
        }

        // 3. Kelola file dokumen: unggah baru atau hapus yang ada
        $fileLamaAkanDihapus = null;
        $fileBaruTersimpan   = null;

        if ($request->hasFile('file_dokumen')) {
            $fileLamaAkanDihapus = $arsip->getOriginal('file_dokumen');
            $fileBaruTersimpan   = $this->simpanFile($request->file('file_dokumen'), $arsip->kode_berkas);
            $arsip->file_dokumen = $fileBaruTersimpan;
            $perubahan[]         = 'File dokumen digital diperbarui';
        } elseif ($request->boolean('hapus_file') && $arsip->file_dokumen) {
            $fileLamaAkanDihapus = $arsip->file_dokumen;
            $arsip->file_dokumen = null;
            $perubahan[]         = 'File dokumen digital dihapus';
        }

        // Jika tidak ada perubahan sama sekali, hindari query save dan log aktivitas palsu
        if ($arsip->isClean() && empty($perubahan)) {
            return redirect()->route('arsip.index')->with('info', 'Tidak ada perubahan data yang disimpan.');
        }

        $detailPerubahan = !empty($perubahan) ? implode(', ', $perubahan) : 'data umum';

        // 4. Simpan dalam transaksi database
        try {
            DB::transaction(function () use ($arsip, $detailPerubahan) {
                $arsip->save();
                ActivityLog::catat('EDIT', 'mengubah ' . $detailPerubahan . ' pada arsip ' . $arsip->kode_berkas);
            });
        } catch (\Throwable $e) {
            // Hapus file baru jika operasi database gagal
            if ($fileBaruTersimpan) {
                $this->hapusFile($fileBaruTersimpan);
            }
            throw $e;
        }

        // Hapus file lama hanya setelah transaksi database berhasil tersimpan
        if ($fileLamaAkanDihapus) {
            $this->hapusFile($fileLamaAkanDihapus);
        }

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

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(self::DISK);

        return response()->file($disk->path($relativePath), [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function history()
    {
        $arsips = Arsip::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(15);

        return view('arsip.history', compact('arsips'));
    }

    public function restore(string $id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $arsip->restore();

        ActivityLog::catat('PULIHKAN', 'memulihkan arsip ' . $arsip->kode_berkas . ' dari riwayat hapus.');

        return redirect()->route('arsip.history')->with('success', 'Arsip berhasil dipulihkan!');
    }

    public function forceDelete(string $id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $kode = $arsip->kode_berkas;
        $file = $arsip->file_dokumen;

        DB::transaction(function () use ($arsip) {
            $arsip->forceDelete();
        });

        if ($file) {
            $this->hapusFile($file);
        }

        ActivityLog::catat('HAPUS PERMANEN', 'menghapus permanen arsip ' . $kode . ' dari riwayat hapus.');

        return redirect()->route('arsip.history')->with('success', 'Arsip ' . $kode . ' berhasil dihapus permanen!');
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

        DB::transaction(function () use ($bisaDihapus) {
            Arsip::whereIn('id', $bisaDihapus->pluck('id'))->delete();
            ActivityLog::catat('HAPUS MASSAL', 'menghapus ' . $bisaDihapus->count() . ' arsip sekaligus.');
        });

        $redirect = redirect()->route('arsip.index')
            ->with('success', $bisaDihapus->count() . ' Arsip berhasil dihapus sekaligus!');

        if ($dipinjam->isNotEmpty()) {
            $redirect->with('warning', $dipinjam->count() . ' arsip dilewati karena sedang dipinjam: '
                . $dipinjam->pluck('kode_berkas')->implode(', ') . '.');
        }

        return $redirect;
    }

    public function printQr(Arsip $arsip)
    {
        $url = route('katalog.show', $arsip->id);
        return view('arsip.qrcode', compact('arsip', 'url'));
    }

    // =========================================================================
    // HELPER FILE DOKUMEN
    // =========================================================================
    private function simpanFile(UploadedFile $file, string $kode): string
    {
        $slug = Str::slug($kode) ?: 'berkas';
        // Akhiran acak agar nama file tidak bisa ditebak
        $nama = $slug . '_' . Str::random(12) . '.' . $file->extension();
        $file->storeAs(self::DIR, $nama, self::DISK);

        return $nama;
    }

    private function hapusFile(?string $nama): void
    {
        if (empty($nama)) {
            return;
        }

        Storage::disk(self::DISK)->delete(self::DIR . '/' . $nama);
        Storage::disk('public')->delete(self::DIR . '/' . $nama); // sisa file lama, jika ada
    }
}
