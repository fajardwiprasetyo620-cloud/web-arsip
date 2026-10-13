<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji perlindungan penghapusan arsip yang sedang dipinjam (satuan maupun massal),
 * bagian dari perbaikan Tahap 2.
 */
class ArsipDeletionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_arsip_yang_sedang_dipinjam_tidak_bisa_dihapus(): void
    {
        $arsip = Arsip::factory()->dipinjam()->create();

        $response = $this->actingAs($this->admin)->delete(route('arsip.destroy', $arsip));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('arsips', ['id' => $arsip->id, 'deleted_at' => null]);
    }

    public function test_arsip_yang_tersedia_bisa_dihapus(): void
    {
        $arsip = Arsip::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('arsip.destroy', $arsip));

        $response->assertSessionHas('success');
        $this->assertSoftDeleted('arsips', ['id' => $arsip->id]);
    }

    /** Hapus massal melewati arsip yang dipinjam, tapi tetap menghapus sisanya. */
    public function test_hapus_massal_melewati_arsip_yang_dipinjam(): void
    {
        $tersedia = Arsip::factory()->count(2)->create();
        $dipinjam = Arsip::factory()->dipinjam()->create();

        $response = $this->actingAs($this->admin)->post(route('arsip.bulkDelete'), [
            'ids' => [...$tersedia->pluck('id'), $dipinjam->id],
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionHas('warning');

        foreach ($tersedia as $a) {
            $this->assertSoftDeleted('arsips', ['id' => $a->id]);
        }
        $this->assertDatabaseHas('arsips', ['id' => $dipinjam->id, 'deleted_at' => null]);
    }
}
