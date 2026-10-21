<?php

namespace Tests\Feature;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji urutan halaman Peminjaman: transaksi yang memiliki arsip TERLAMBAT
 * (masih Dipinjam dan sudah lewat tenggat) harus tampil paling atas,
 * supaya tidak terlewat walau datanya sudah banyak.
 */
class PeminjamanUrutanTerlambatTest extends TestCase
{
    use RefreshDatabase;

    private function kunciTransaksi($response): array
    {
        return $response->viewData('groupedPeminjamans')->getCollection()->keys()->all();
    }

    public function test_transaksi_terlambat_tampil_paling_atas_walau_dibuat_lebih_lama(): void
    {
        $admin = User::factory()->create();

        // Dibuat LEBIH DULU dan sudah terlambat
        Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Budi',
            'created_at'    => now()->subDays(2),
        ]);
        // Dibuat BELAKANGAN dan belum terlambat (tenggat masih 7 hari lagi)
        Peminjaman::factory()->create([
            'nama_peminjam' => 'Andi',
            'created_at'    => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));

        $response->assertOk();
        $kunci = $this->kunciTransaksi($response);

        $this->assertCount(2, $kunci);
        $this->assertStringStartsWith('Budi#', $kunci[0]); // terlambat -> paling atas
        $this->assertStringStartsWith('Andi#', $kunci[1]);
        $this->assertSame(1, $response->viewData('jumlahTransaksiTerlambat'));
    }

    public function test_yang_paling_lama_lewat_tenggat_ada_di_paling_atas(): void
    {
        $admin = User::factory()->create();

        Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Dika',
            'tenggat_waktu' => now()->subDays(2)->toDateString(),
        ]);
        Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Eka',
            'tenggat_waktu' => now()->subDays(9)->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));
        $kunci = $this->kunciTransaksi($response);

        $this->assertStringStartsWith('Eka#', $kunci[0]);  // lewat 9 hari
        $this->assertStringStartsWith('Dika#', $kunci[1]); // lewat 2 hari
    }

    public function test_arsip_yang_sudah_dikembalikan_tidak_dianggap_terlambat(): void
    {
        $admin = User::factory()->create();

        Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Budi',
            'created_at'    => now()->subDays(2),
        ]);
        // Tenggatnya sudah lewat, tapi arsip SUDAH dikembalikan -> bukan terlambat
        Peminjaman::factory()->dikembalikan()->create([
            'nama_peminjam'  => 'Cici',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tenggat_waktu'  => now()->subDays(3)->toDateString(),
            'created_at'     => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));
        $kunci = $this->kunciTransaksi($response);

        $this->assertStringStartsWith('Budi#', $kunci[0]);
        $this->assertStringStartsWith('Cici#', $kunci[1]);
        $this->assertSame(1, $response->viewData('jumlahTransaksiTerlambat'));
        $this->assertNotContains($kunci[1], $response->viewData('kunciTerlambat'));
    }
}
