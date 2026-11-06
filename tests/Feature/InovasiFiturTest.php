<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InovasiFiturTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_quick_search_command_palette_berhasil(): void
    {
        Arsip::create([
            'kode_berkas' => 'BPJS-KLAIM-001',
            'nama_berkas' => 'Klaim JHT Budi Santoso',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => '10',
            'status' => 'Tersedia',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('arsip.quickSearch', ['q' => 'Budi']));

        $response->assertOk();
        $response->assertJsonStructure([
            'arsips' => [
                '*' => ['id', 'kode', 'nama', 'lokasi', 'status', 'has_file', 'url', 'edit_url'],
            ],
        ]);
        $response->assertJsonFragment(['kode' => 'BPJS-KLAIM-001']);
    }

    public function test_cetak_laporan_eksekutif_pdf_berhasil(): void
    {
        Arsip::create([
            'kode_berkas' => 'BPJS-KLAIM-002',
            'nama_berkas' => 'Klaim JKK Siti Rahma',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 2,
            'box' => '11',
            'status' => 'Tersedia',
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard.executivePdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_filter_retensi_arsip(): void
    {
        // Arsip Aktif (kurang dari 2 tahun lalu)
        Arsip::create([
            'kode_berkas' => 'RET-AKTIF',
            'nama_berkas' => 'Dokumen Aktif',
            'tanggal_berkas' => now()->subMonths(6)->toDateString(),
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => '01',
        ]);

        // Arsip Inaktif (antara 2 - 5 tahun lalu)
        Arsip::create([
            'kode_berkas' => 'RET-INAKTIF',
            'nama_berkas' => 'Dokumen Inaktif',
            'tanggal_berkas' => now()->subYears(3)->toDateString(),
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => '01',
        ]);

        // Arsip Jatuh Tempo (> 5 tahun lalu)
        Arsip::create([
            'kode_berkas' => 'RET-TEMPO',
            'nama_berkas' => 'Dokumen Jatuh Tempo Retensi',
            'tanggal_berkas' => now()->subYears(6)->toDateString(),
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => '01',
        ]);

        $this->assertSame(1, Arsip::filter(['retensi' => 'aktif'])->count());
        $this->assertSame(1, Arsip::filter(['retensi' => 'inaktif'])->count());
        $this->assertSame(1, Arsip::filter(['retensi' => 'jatuh_tempo'])->count());
    }
}
