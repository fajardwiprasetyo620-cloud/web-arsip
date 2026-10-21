<?php

namespace App\Imports;

use App\Models\Arsip;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArsipImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /** Jumlah baris yang berhasil masuk dan yang dilewati (dibaca controller untuk pesan hasil). */
    public int $masuk = 0;
    public int $dilewati = 0;

    /** Daftar kode berkas yang sudah ada (termasuk Riwayat Hapus), diambil SEKALI di awal. */
    private array $kodeAda = [];

    public function __construct()
    {
        foreach (Arsip::withTrashed()->pluck('kode_berkas') as $kode) {
            $this->kodeAda[mb_strtolower(trim($kode))] = true;
        }
    }

    public function model(array $row)
    {
        // Baris yang benar-benar kosong diabaikan tanpa dihitung
        if (collect($row)->filter(fn ($v) => $v !== null && trim((string) $v) !== '')->isEmpty()) {
            return null;
        }

        $kode = trim((string) ($row['kode_berkas'] ?? ''));
        $nama = trim((string) ($row['nama_berkas'] ?? ''));

        // 1. Kode atau nama kosong: lewati
        if ($kode === '' || $nama === '') {
            $this->dilewati++;
            return null;
        }

        // 2. Anti-duplikasi (termasuk duplikat di dalam file Excel yang sama)
        $kunci = mb_strtolower($kode);
        if (isset($this->kodeAda[$kunci])) {
            $this->dilewati++;
            return null;
        }
        $this->kodeAda[$kunci] = true;

        $ruangan = trim((string) ($row['ruangan'] ?? ''));
        $rak     = trim((string) ($row['rak'] ?? ''));
        $box     = trim((string) ($row['box'] ?? ''));
        $baris   = $row['baris'] ?? null;
        $tanggal = $this->parseTanggal($row['tanggal_berkas'] ?? $row['tanggal'] ?? null);

        $this->masuk++;

        return new Arsip([
            'kode_berkas' => $kode,
            'nama_berkas' => $nama,
            'ruangan'     => $ruangan !== '' ? $ruangan : 'Belum Diatur',
            'rak'         => $rak !== '' ? $rak : 'Belum Diatur',
            'baris'       => (is_numeric($baris) && (int) $baris >= 1) ? (int) $baris : 1,
            'box'         => $box !== '' ? $box : '0',
            'tanggal_berkas' => $tanggal,
            'status'      => 'Tersedia',
        ]);
    }

    /**
     * Membaca tanggal dari sel Excel. Mendukung: angka seri Excel, "2026-10-01",
     * dan "01/10/2026" (hari/bulan/tahun). Nilai kosong atau tidak valid -> null
     * (baris tetap diimpor, hanya tanggalnya yang dikosongkan).
     */
    private function parseTanggal($nilai): ?string
    {
        if ($nilai === null || trim((string) $nilai) === '') {
            return null;
        }

        try {
            // Excel menyimpan sel tanggal sebagai angka seri (hari sejak 1900)
            if (is_numeric($nilai)) {
                $seri = (float) $nilai;

                return ($seri >= 20000 && $seri <= 80000)
                    ? ExcelDate::excelToDateTimeObject($seri)->format('Y-m-d')
                    : null;
            }

            $teks = trim((string) $nilai);

            if (preg_match('#^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$#', $teks, $m)) {
                // Hari/bulan/tahun; tolak tanggal yang tidak ada (mis. 31/02)
                return checkdate((int) $m[2], (int) $m[1], (int) $m[3])
                    ? sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1])
                    : null;
            }

            return Carbon::parse($teks)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** Judul kolom ada di baris ke-4 Excel */
    public function headingRow(): int
    {
        return 4;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
