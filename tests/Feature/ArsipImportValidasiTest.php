<?php

namespace Tests\Feature;

use App\Imports\ArsipImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArsipImportValidasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_impor_menolak_ruangan_tidak_valid(): void
    {
        $import = new ArsipImport;
        $row = [
            'kode_berkas' => 'TEST-001',
            'nama_berkas' => 'Dokumen Uji',
            'ruangan' => 'Ruangan Tidak Terdaftar',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => 'Box 01',
        ];

        $model = $import->model($row);

        $this->assertNull($model);
        $this->assertSame(1, $import->dilewati);
        $this->assertStringContainsString('Ruangan \'Ruangan Tidak Terdaftar\' tidak valid', $import->catatan[0]);
    }

    public function test_impor_menolak_baris_melebihi_kapasitas_ruangan(): void
    {
        $import = new ArsipImport;
        // Ruang Arsip Kecil maksimal baris 4
        $row = [
            'kode_berkas' => 'TEST-002',
            'nama_berkas' => 'Dokumen Uji 2',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 7, // melebihi kapasitas 4
            'box' => 'Box 01',
        ];

        $model = $import->model($row);

        $this->assertNull($model);
        $this->assertSame(1, $import->dilewati);
        $this->assertStringContainsString('maksimal baris 4', $import->catatan[0]);
    }

    public function test_impor_menolak_baris_pecahan(): void
    {
        $import = new ArsipImport;
        $model = $import->model([
            'kode_berkas' => 'TEST-004',
            'nama_berkas' => 'Dokumen Uji 4',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1.5,
            'box' => 'Box 01',
        ]);

        $this->assertNull($model);
        $this->assertSame(1, $import->dilewati);
        $this->assertStringContainsString('bilangan bulat', $import->catatan[0]);
    }

    public function test_impor_menerima_baris_dengan_format_desimal_bulat(): void
    {
        $import = new ArsipImport;
        $model = $import->model([
            'kode_berkas' => 'TEST-005',
            'nama_berkas' => 'Dokumen Uji 5',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1.0,
            'box' => 'Box 01',
        ]);

        $this->assertNotNull($model);
        $this->assertSame(1, $model->baris);
    }

    public function test_impor_menolak_rak_atau_box_kosong(): void
    {
        foreach (['rak' => '', 'box' => ''] as $kolom => $nilai) {
            $import = new ArsipImport;
            $row = [
                'kode_berkas' => 'TEST-'.strtoupper($kolom),
                'nama_berkas' => 'Dokumen Uji',
                'ruangan' => 'Ruang Arsip Kecil',
                'rak' => 'Rak A',
                'baris' => 1,
                'box' => 'Box 01',
                $kolom => $nilai,
            ];

            $this->assertNull($import->model($row));
            $this->assertSame(1, $import->dilewati);
            $this->assertStringContainsString('Rak dan Box wajib diisi', $import->catatan[0]);
        }
    }

    public function test_impor_mencatat_peringatan_tanggal_masa_depan(): void
    {
        $import = new ArsipImport;
        $row = [
            'kode_berkas' => 'TEST-003',
            'nama_berkas' => 'Dokumen Uji 3',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 2,
            'box' => 'Box 01',
            'tanggal_berkas' => now()->addYear()->format('Y-m-d'),
        ];

        $model = $import->model($row);

        $this->assertNotNull($model);
        $this->assertNull($model->tanggal_berkas); // dikosongkan karena masa depan
        $this->assertStringContainsString('di masa depan', $import->catatan[0]);
    }

    public function test_rincian_catatan_dibatasi_tetapi_jumlah_total_akurat(): void
    {
        $import = new ArsipImport;

        for ($i = 1; $i <= 60; $i++) {
            $import->model([
                'kode_berkas' => "TEST-{$i}",
                'nama_berkas' => "Dokumen Uji {$i}",
                'ruangan' => 'Ruangan Tidak Valid',
                'rak' => 'Rak A',
                'baris' => 1,
                'box' => 'Box 01',
            ]);
        }

        $this->assertSame(60, $import->jumlahCatatan);
        $this->assertCount(50, $import->catatan);
    }
}
