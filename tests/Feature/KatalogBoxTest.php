<?php

namespace Tests\Feature;

use App\Models\Arsip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji halaman publik isi box (dituju lewat scan QR Box).
 */
class KatalogBoxTest extends TestCase
{
    use RefreshDatabase;

    private array $lokasi = [
        'ruangan' => 'Ruang Arsip Kecil',
        'rak' => 'A',
        'baris' => 3,
        'box' => '3',
    ];

    public function test_menampilkan_semua_berkas_di_box_tanpa_login(): void
    {
        Arsip::factory()->create($this->lokasi + ['kode_berkas' => 'BOX-AAA-001']);
        Arsip::factory()->create($this->lokasi + ['kode_berkas' => 'BOX-AAA-002']);
        // Box lain (nomor box berbeda) tidak boleh ikut tampil
        Arsip::factory()->create(array_merge($this->lokasi, ['box' => '4', 'kode_berkas' => 'BOX-ZZZ-999']));

        $response = $this->get(route('katalog.box', $this->lokasi));

        $response->assertOk()
            ->assertSee('BOX-AAA-001')
            ->assertSee('BOX-AAA-002')
            ->assertDontSee('BOX-ZZZ-999');
    }

    public function test_box_yang_tidak_ada_berisi_berkas_menghasilkan_404(): void
    {
        $this->get(route('katalog.box', $this->lokasi))->assertNotFound();
    }

    public function test_alamat_tanpa_parameter_lengkap_menghasilkan_404(): void
    {
        $this->get(route('katalog.box', ['ruangan' => 'Ruang Arsip Kecil']))->assertNotFound();
    }

    public function test_menampilkan_jumlah_berkas_yang_sedang_dipinjam(): void
    {
        Arsip::factory()->create($this->lokasi);
        Arsip::factory()->dipinjam()->create($this->lokasi);

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertSee('2 berkas')
            ->assertSee('1 sedang dipinjam');
    }

    public function test_berkas_yang_sudah_dihapus_tidak_tampil(): void
    {
        Arsip::factory()->create($this->lokasi + ['kode_berkas' => 'BOX-ADA-001']);
        $dihapus = Arsip::factory()->create($this->lokasi + ['kode_berkas' => 'BOX-HAPUS-002']);
        $dihapus->delete(); // soft delete

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertSee('BOX-ADA-001')
            ->assertDontSee('BOX-HAPUS-002');
    }
}
