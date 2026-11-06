<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji kolom "Tanggal Berkas" pada form tambah/edit arsip.
 */
class ArsipTanggalBerkasTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $extra = []): array
    {
        return array_merge([
            'kode_berkas' => 'TGL-001',
            'nama_berkas' => 'Berkas uji tanggal',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'A',
            'baris' => 2,
            'box' => '5',
        ], $extra);
    }

    public function test_tanggal_berkas_tersimpan_saat_arsip_ditambah(): void
    {
        $admin = User::factory()->create();
        $tanggal = now()->subDays(5)->toDateString();

        $this->actingAs($admin)
            ->post(route('arsip.store'), $this->payload(['tanggal_berkas' => $tanggal]))
            ->assertRedirect(route('arsip.index'));

        $this->assertDatabaseHas('arsips', [
            'kode_berkas' => 'TGL-001',
            'tanggal_berkas' => $tanggal,
        ]);
    }

    public function test_tanggal_berkas_boleh_dikosongkan(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('arsip.store'), $this->payload(['tanggal_berkas' => '']))
            ->assertRedirect(route('arsip.index'));

        $this->assertDatabaseHas('arsips', [
            'kode_berkas' => 'TGL-001',
            'tanggal_berkas' => null,
        ]);
    }

    public function test_tanggal_di_masa_depan_ditolak(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('arsip.store'), $this->payload([
                'tanggal_berkas' => now()->addDays(3)->toDateString(),
            ]))
            ->assertSessionHasErrors('tanggal_berkas');

        $this->assertDatabaseMissing('arsips', ['kode_berkas' => 'TGL-001']);
    }

    public function test_tanggal_dengan_format_tidak_valid_ditolak(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('arsip.store'), $this->payload(['tanggal_berkas' => 'bukan-tanggal']))
            ->assertSessionHasErrors('tanggal_berkas');
    }

    public function test_tanggal_berkas_bisa_diubah_lewat_edit(): void
    {
        $admin = User::factory()->create();
        $arsip = Arsip::factory()->create([
            'kode_berkas' => 'TGL-001',
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'A',
            'baris' => 2,
            'box' => '5',
        ]);
        $tanggal = now()->subDays(10)->toDateString();

        $this->actingAs($admin)
            ->put(route('arsip.update', $arsip), $this->payload(['tanggal_berkas' => $tanggal]))
            ->assertRedirect(route('arsip.index'));

        $this->assertDatabaseHas('arsips', ['id' => $arsip->id, 'tanggal_berkas' => $tanggal]);
    }
}
