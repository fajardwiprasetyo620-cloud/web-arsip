<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaAcaraPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_cetak_berita_acara_peminjaman_berhasil(): void
    {
        $arsip = Arsip::factory()->create();
        $pinjam = Peminjaman::factory()->for($arsip, 'arsip')->create([
            'nama_peminjam'  => 'Agus Pratama',
            'divisi'         => 'Kepesertaan',
            'tanggal_pinjam' => '2026-10-15',
        ]);

        $response = $this->actingAs($this->admin)->get(route('peminjaman.bap', [
            'nama'    => 'Agus Pratama',
            'tanggal' => '2026-10-15',
        ]));

        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
    }

    public function test_cetak_berita_acara_tanpa_parameter_menghasilkan_404(): void
    {
        $response = $this->actingAs($this->admin)->get(route('peminjaman.bap'));

        $response->assertNotFound();
    }
}

