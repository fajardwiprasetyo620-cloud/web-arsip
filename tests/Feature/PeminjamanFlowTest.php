<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji alur pinjam -> kembali, termasuk kasus-kasus yang diperbaiki di Tahap 2:
 * tidak boleh ada peminjaman dobel, pengembalian dobel, atau hapus data yang masih aktif.
 */
class PeminjamanFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    /** Meminjamkan arsip yang Tersedia berhasil dan status arsip berubah jadi Dipinjam. */
    public function test_meminjam_arsip_yang_tersedia_berhasil(): void
    {
        $arsip = Arsip::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('peminjaman.store'), [
            'arsip_id' => [$arsip->id],
            'nama_peminjam' => 'Budi Santoso',
            'divisi' => 'Kepesertaan',
            'tanggal_pinjam' => now()->toDateString(),
            'tenggat_waktu' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect(route('peminjaman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('arsips', ['id' => $arsip->id, 'status' => 'Dipinjam']);
        $this->assertDatabaseHas('peminjamans', [
            'arsip_id' => $arsip->id,
            'nama_peminjam' => 'Budi Santoso',
            'status' => 'Dipinjam',
        ]);
    }

    /** Arsip yang sudah berstatus Dipinjam tidak boleh dipinjamkan lagi (mencegah peminjaman dobel). */
    public function test_arsip_yang_sudah_dipinjam_tidak_bisa_dipinjam_lagi(): void
    {
        $arsip = Arsip::factory()->dipinjam()->create();

        // Peminjaman yang sudah ada lebih dulu (sebabnya arsip berstatus Dipinjam)
        $peminjamanLama = Peminjaman::factory()->for($arsip, 'arsip')->create(['status' => 'Dipinjam']);

        $response = $this->actingAs($this->admin)->from(route('peminjaman.create'))->post(route('peminjaman.store'), [
            'arsip_id' => [$arsip->id],
            'nama_peminjam' => 'Siti Aminah',
            'divisi' => 'Klaim',
            'tanggal_pinjam' => now()->toDateString(),
            'tenggat_waktu' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertSessionHasErrors('arsip_id');

        // Hanya ada satu peminjaman untuk arsip ini (yang lama), bukan dua
        $this->assertSame(1, Peminjaman::where('arsip_id', $arsip->id)->count());
        $this->assertTrue(Peminjaman::where('arsip_id', $arsip->id)->first()->is($peminjamanLama));
    }

    /** Pengembalian mengubah status peminjaman dan mengembalikan status arsip jadi Tersedia. */
    public function test_mengembalikan_arsip_berhasil(): void
    {
        $arsip = Arsip::factory()->dipinjam()->create();
        $pinjam = Peminjaman::factory()->for($arsip, 'arsip')->create(['status' => 'Dipinjam']);

        $response = $this->actingAs($this->admin)->patch(route('peminjaman.update', $pinjam), []);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('peminjamans', ['id' => $pinjam->id, 'status' => 'Dikembalikan']);
        $this->assertDatabaseHas('arsips', ['id' => $arsip->id, 'status' => 'Tersedia']);
    }

    /** Peminjaman yang sudah Dikembalikan tidak bisa diproses kembali dua kali. */
    public function test_pengembalian_tidak_bisa_diproses_dua_kali(): void
    {
        $arsip = Arsip::factory()->create(); // sudah Tersedia (sudah dikembalikan sebelumnya)
        $pinjam = Peminjaman::factory()->for($arsip, 'arsip')->dikembalikan()->create();

        $response = $this->actingAs($this->admin)->patch(route('peminjaman.update', $pinjam), []);

        $response->assertSessionHas('error');
        // Status arsip tidak ikut berubah lagi
        $this->assertDatabaseHas('arsips', ['id' => $arsip->id, 'status' => 'Tersedia']);
    }

    /** Riwayat peminjaman yang masih berstatus Dipinjam tidak boleh dihapus. */
    public function test_riwayat_peminjaman_yang_masih_dipinjam_tidak_bisa_dihapus(): void
    {
        $arsip = Arsip::factory()->dipinjam()->create();
        $pinjam = Peminjaman::factory()->for($arsip, 'arsip')->create(['status' => 'Dipinjam']);

        $response = $this->actingAs($this->admin)->delete(route('peminjaman.destroy', $pinjam));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('peminjamans', ['id' => $pinjam->id]);
    }

    /** Riwayat peminjaman yang sudah Dikembalikan boleh dihapus. */
    public function test_riwayat_peminjaman_yang_sudah_dikembalikan_bisa_dihapus(): void
    {
        $pinjam = Peminjaman::factory()->dikembalikan()->create();

        $response = $this->actingAs($this->admin)->delete(route('peminjaman.destroy', $pinjam));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('peminjamans', ['id' => $pinjam->id]);
    }
}
