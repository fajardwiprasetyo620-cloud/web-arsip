<?php

namespace Tests\Feature;

use App\Imports\ArsipImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji pembacaan kolom "Tanggal Berkas" saat import Excel.
 */
class ArsipImportTanggalTest extends TestCase
{
    use RefreshDatabase;

    private function baris(array $extra = []): array
    {
        return array_merge([
            'kode_berkas' => 'IMP-001',
            'nama_berkas' => 'Berkas impor',
            'ruangan'     => 'Ruang Arsip Kecil',
            'rak'         => 'A',
            'baris'       => 1,
            'box'         => '3',
        ], $extra);
    }

    private function tanggalHasil(array $extra): ?string
    {
        return (new ArsipImport)->model($this->baris($extra))->tanggal_berkas;
    }

    public function test_format_tahun_bulan_hari_terbaca(): void
    {
        $this->assertSame('2026-10-01', $this->tanggalHasil(['tanggal_berkas' => '2026-10-01']));
    }

    public function test_format_hari_bulan_tahun_terbaca_sebagai_hari_dulu(): void
    {
        $this->assertSame('2026-10-01', $this->tanggalHasil(['tanggal_berkas' => '01/10/2026']));
        $this->assertSame('2026-02-01', $this->tanggalHasil(['tanggal_berkas' => '1/2/2026']));
    }

    public function test_angka_seri_excel_terbaca(): void
    {
        // 46296 = 1 Oktober 2026 pada penanggalan Excel
        $this->assertSame('2026-10-01', $this->tanggalHasil(['tanggal_berkas' => 46296]));
    }

    public function test_tanggal_kosong_atau_tidak_valid_menjadi_null_tanpa_membatalkan_baris(): void
    {
        $this->assertNull($this->tanggalHasil(['tanggal_berkas' => '']));
        $this->assertNull($this->tanggalHasil(['tanggal_berkas' => 'abc']));
        $this->assertNull($this->tanggalHasil(['tanggal_berkas' => '31/02/2026']));
    }

    public function test_file_tanpa_kolom_tanggal_tetap_bisa_diimpor(): void
    {
        $arsip = (new ArsipImport)->model($this->baris());

        $this->assertNotNull($arsip);
        $this->assertSame('IMP-001', $arsip->kode_berkas);
        $this->assertNull($arsip->tanggal_berkas);
    }
}
