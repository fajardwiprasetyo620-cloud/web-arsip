<?php

namespace App\Imports;

use App\Models\Arsip;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ArsipImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    private const MAKS_CATATAN = 50;

    /** Jumlah baris yang berhasil masuk dan yang dilewati. */
    public int $masuk = 0;

    public int $dilewati = 0;

    /** Rincian catatan error/peringatan per baris agar admin tahu letak kesalahan. */
    public array $catatan = [];

    public int $jumlahCatatan = 0;

    /** Penghitung baris fisik di file Excel (judul di baris ke-4, data mulai baris ke-5). */
    private int $barisKe = 4;

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
        $this->barisKe++;

        // Baris yang benar-benar kosong diabaikan tanpa dihitung
        if (collect($row)->filter(fn ($v) => $v !== null && (! is_scalar($v) || trim((string) $v) !== ''))->isEmpty()) {
            return null;
        }

        $kode = $this->nilaiTeks($row['kode_berkas'] ?? null);
        $nama = $this->nilaiTeks($row['nama_berkas'] ?? null);

        // 1. Validasi Kode Berkas & Nama Berkas wajib diisi
        if ($kode === '' || $nama === '') {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe}: Kode Berkas atau Nama Berkas kosong.");

            return null;
        }

        if (mb_strlen($kode) > 100 || mb_strlen($nama) > 255) {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Kode maksimal 100 karakter dan Nama Berkas maksimal 255 karakter.");

            return null;
        }

        // 2. Anti-duplikasi (termasuk duplikat di dalam file Excel yang sama)
        $kunci = mb_strtolower($kode);
        if (isset($this->kodeAda[$kunci])) {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Kode berkas sudah terdaftar di sistem.");

            return null;
        }

        // 3. Validasi Ruangan Fisik
        $ruangan = $this->nilaiTeks($row['ruangan'] ?? null);
        $daftarRuanganValid = array_keys(Arsip::KAPASITAS_BARIS);

        if ($ruangan === '' || ! in_array($ruangan, $daftarRuanganValid, true)) {
            $this->dilewati++;
            $daftarPilihan = implode(', ', $daftarRuanganValid);
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Ruangan '{$ruangan}' tidak valid. Pilihan: {$daftarPilihan}.");

            return null;
        }

        // 4. Validasi Kapasitas Baris Rak sesuai Ruangan
        $baris = $row['baris'] ?? null;
        $maxBaris = Arsip::KAPASITAS_BARIS[$ruangan];
        $barisNumerik = is_numeric($baris) ? (float) $baris : null;

        if ($barisNumerik === null || ! is_finite($barisNumerik) || floor($barisNumerik) !== $barisNumerik
            || $barisNumerik < 1 || $barisNumerik > $maxBaris) {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Nilai baris '{$baris}' tidak valid untuk {$ruangan}; gunakan bilangan bulat 1 sampai {$maxBaris} (maksimal baris {$maxBaris}).");

            return null;
        }

        $rak = $this->nilaiTeks($row['rak'] ?? null);
        $box = $this->nilaiTeks($row['box'] ?? null);

        if ($rak === '' || $box === '') {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Rak dan Box wajib diisi agar lokasi arsip tidak keliru.");

            return null;
        }

        if (mb_strlen($rak) > 50 || mb_strlen($box) > 50) {
            $this->dilewati++;
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Panjang Rak dan Box maksimal 50 karakter.");

            return null;
        }

        // 5. Validasi Tanggal Berkas
        $rawTanggal = $row['tanggal_berkas'] ?? $row['tanggal'] ?? null;
        $tanggal = $this->parseTanggal($rawTanggal, $kode);

        // Jika semua lolos, tandai kode sudah dipakai dan simpan arsip
        $this->kodeAda[$kunci] = true;
        $this->masuk++;

        return new Arsip([
            'kode_berkas' => $kode,
            'nama_berkas' => $nama,
            'ruangan' => $ruangan,
            'rak' => $rak,
            'baris' => (int) $barisNumerik,
            'box' => $box,
            'tanggal_berkas' => $tanggal,
            'status' => 'Tersedia',
        ]);
    }

    private function nilaiTeks(mixed $nilai): string
    {
        return is_scalar($nilai) ? trim((string) $nilai) : '';
    }

    private function catat(string $pesan): void
    {
        $this->jumlahCatatan++;

        if (count($this->catatan) < self::MAKS_CATATAN) {
            $this->catatan[] = $pesan;
        }
    }

    /**
     * Membaca tanggal dari sel Excel. Mendukung: angka seri Excel, "YYYY-MM-DD",
     * dan "DD/MM/YYYY". Jika tanggal rusak atau di masa depan, catat peringatan dan kembalikan null.
     */
    private function parseTanggal(mixed $nilai, string $kode): ?string
    {
        if ($nilai === null || $nilai === '') {
            return null;
        }

        if (! is_scalar($nilai)) {
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Nilai tanggal tidak valid, dikosongkan.");

            return null;
        }

        try {
            $hasilTanggal = null;

            // Excel menyimpan sel tanggal sebagai angka seri (hari sejak 1900)
            if (is_numeric($nilai)) {
                $seri = (float) $nilai;
                if ($seri >= 20000 && $seri <= 80000) {
                    $hasilTanggal = ExcelDate::excelToDateTimeObject($seri)->format('Y-m-d');
                }
            } else {
                $teks = trim((string) $nilai);
                if (preg_match('#^(\d{1,2})[/-](\d{1,2})[/-](\d{4})$#', $teks, $m)) {
                    if (checkdate((int) $m[2], (int) $m[1], (int) $m[3])) {
                        $hasilTanggal = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                    }
                } else {
                    $hasilTanggal = Carbon::parse($teks)->format('Y-m-d');
                }
            }

            if ($hasilTanggal !== null) {
                // Cegah tanggal berkas di masa depan
                if ($hasilTanggal > today()->toDateString()) {
                    $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Tanggal '{$hasilTanggal}' di masa depan (tidak valid), dikosongkan.");

                    return null;
                }

                return $hasilTanggal;
            }

            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Format tanggal '{$nilai}' tidak dikenali, dikosongkan.");

            return null;
        } catch (\Throwable $e) {
            $this->catat("Baris {$this->barisKe} (Kode: {$kode}): Tanggal '{$nilai}' tidak valid, dikosongkan.");

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
