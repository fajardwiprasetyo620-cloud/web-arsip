<?php

namespace Tests\Feature;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji pemisahan daftar peminjaman berdasarkan status setiap berkas.
 */
class PeminjamanUrutanTerlambatTest extends TestCase
{
    use RefreshDatabase;

    public function test_berkas_terlambat_diurutkan_paling_lama_lewat_tenggat(): void
    {
        $admin = User::factory()->create();

        $terlambatLama = Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Budi',
            'tenggat_waktu' => now()->subDays(9)->toDateString(),
            'created_at'    => now()->subDays(2),
        ]);
        $terlambatBaru = Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Andi',
            'tenggat_waktu' => now()->subDays(2)->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));

        $response->assertOk();
        $idsTerlambat = $response->viewData('peminjamanTerlambat')->getCollection()->pluck('id')->all();
        $this->assertSame([$terlambatLama->id, $terlambatBaru->id], $idsTerlambat);
    }

    public function test_arsip_yang_sudah_dikembalikan_tidak_dianggap_terlambat(): void
    {
        $admin = User::factory()->create();

        $terlambat = Peminjaman::factory()->terlambat()->create([
            'nama_peminjam' => 'Budi',
            'created_at'    => now()->subDays(2),
        ]);
        $dikembalikan = Peminjaman::factory()->dikembalikan()->create([
            'nama_peminjam'  => 'Cici',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tenggat_waktu'  => now()->subDays(3)->toDateString(),
            'created_at'     => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));

        $this->assertSame([$terlambat->id], $response->viewData('peminjamanTerlambat')->getCollection()->pluck('id')->all());
        $this->assertSame([$dikembalikan->id], $response->viewData('peminjamanDikembalikan')->getCollection()->pluck('id')->all());
        $this->assertTrue($response->viewData('peminjamanAktif')->isEmpty());
    }

    public function test_transaksi_campuran_menempatkan_setiap_berkas_di_tabel_statusnya_saja(): void
    {
        $admin = User::factory()->create();
        $dataTransaksi = [
            'nama_peminjam' => 'Dian Anggraini',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
        ];

        Peminjaman::factory()->terlambat()->create($dataTransaksi);
        Peminjaman::factory()->create($dataTransaksi);
        Peminjaman::factory()->dikembalikan()->create($dataTransaksi);

        $response = $this->actingAs($admin)->get(route('peminjaman.index'));

        $response->assertOk()
            ->assertSee('Terlambat')
            ->assertSee('Dipinjam')
            ->assertSee('Dikembalikan');

        $terlambatIds = $response->viewData('peminjamanTerlambat')->getCollection()->pluck('id')->all();
        $aktifIds = $response->viewData('peminjamanAktif')->getCollection()->pluck('id')->all();
        $dikembalikanIds = $response->viewData('peminjamanDikembalikan')->getCollection()->pluck('id')->all();
        $this->assertCount(1, $terlambatIds);
        $this->assertCount(1, $aktifIds);
        $this->assertCount(1, $dikembalikanIds);
        $this->assertCount(3, array_unique(array_merge($terlambatIds, $aktifIds, $dikembalikanIds)));
    }
}
