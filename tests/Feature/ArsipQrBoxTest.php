<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji cetak stiker QR per BOX (satu stiker = satu box, bukan per berkas).
 */
class ArsipQrBoxTest extends TestCase
{
    use RefreshDatabase;

    private function arsipDi(string $box, array $extra = []): Arsip
    {
        return Arsip::factory()->create(array_merge([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak'     => 'A',
            'baris'   => 3,
            'box'     => $box,
        ], $extra));
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('arsip.qrcode.box', ['semua' => 1]))
            ->assertRedirect(route('login'));
    }

    public function test_beberapa_berkas_di_box_yang_sama_hanya_menghasilkan_satu_stiker(): void
    {
        $admin = User::factory()->create();
        $a1 = $this->arsipDi('3');
        $a2 = $this->arsipDi('3');
        $this->arsipDi('4'); // box lain, tidak dipilih

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.box', ['ids' => [$a1->id, $a2->id]]));

        $response->assertOk();
        $this->assertCount(1, $response->viewData('boxes'));
    }

    public function test_jumlah_berkas_dihitung_dari_seluruh_isi_box_bukan_hanya_yang_dipilih(): void
    {
        $admin = User::factory()->create();
        $dipilih = $this->arsipDi('3');
        $this->arsipDi('3');
        $this->arsipDi('3', ['status' => 'Dipinjam']);

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.box', ['ids' => [$dipilih->id]]));

        $box = $response->viewData('boxes')->first();
        $this->assertEquals(3, $box->jumlah);
        $this->assertEquals(1, $box->dipinjam);
    }

    public function test_semua_box_menghasilkan_satu_stiker_untuk_setiap_box(): void
    {
        $admin = User::factory()->create();
        $this->arsipDi('3');
        $this->arsipDi('3');
        $this->arsipDi('4');
        $this->arsipDi('5');

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.box', ['semua' => 1]));

        $response->assertOk();
        $this->assertCount(3, $response->viewData('boxes')); // box 3, 4, 5
        $this->assertGreaterThanOrEqual(3, substr_count($response->getContent(), '<svg'));
    }

    public function test_tanpa_pilihan_dan_tanpa_semua_ditolak_dengan_pesan_validasi(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('arsip.qrcode.box'))
            ->assertSessionHasErrors('ids');
    }
}
