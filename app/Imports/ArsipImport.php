<?php

namespace App\Imports;

use App\Models\Arsip;
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

        $this->masuk++;

        return new Arsip([
            'kode_berkas' => $kode,
            'nama_berkas' => $nama,
            'ruangan'     => $ruangan !== '' ? $ruangan : 'Belum Diatur',
            'rak'         => $rak !== '' ? $rak : 'Belum Diatur',
            'baris'       => (is_numeric($baris) && (int) $baris >= 1) ? (int) $baris : 1,
            'box'         => $box !== '' ? $box : '0',
            'status'      => 'Tersedia',
        ]);
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
