<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji halaman cetak QR Code massal (banyak stiker dalam satu lembar).
 */
class ArsipQrMassalTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $arsip = Arsip::factory()->create();

        $this->get(route('arsip.qrcode.bulk', ['ids' => [$arsip->id]]))
            ->assertRedirect(route('login'));
    }

    public function test_hanya_arsip_yang_dipilih_yang_dicetak(): void
    {
        $admin = User::factory()->create();
        $a = Arsip::factory()->create(['kode_berkas' => 'QR-AAA-001']);
        $b = Arsip::factory()->create(['kode_berkas' => 'QR-BBB-002']);
        Arsip::factory()->create(['kode_berkas' => 'QR-CCC-003']); // tidak dipilih

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.bulk', ['ids' => [$a->id, $b->id]]));

        $response->assertOk();
        $response->assertSee('QR-AAA-001');
        $response->assertSee('QR-BBB-002');
        $response->assertDontSee('QR-CCC-003');
        $this->assertCount(2, $response->viewData('arsips'));
    }

    public function test_setiap_label_memuat_gambar_qr(): void
    {
        $admin = User::factory()->create();
        $arsips = Arsip::factory()->count(2)->create();

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.bulk', ['ids' => $arsips->pluck('id')->all()]));

        $response->assertOk();
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), '<svg'));
    }

    public function test_tanpa_pilihan_ditolak_dengan_pesan_validasi(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get(route('arsip.qrcode.bulk'))
            ->assertSessionHasErrors('ids');
    }

    public function test_peringatan_tampil_jika_dibuka_lewat_alamat_lokal(): void
    {
        $admin = User::factory()->create();
        $arsip = Arsip::factory()->create();

        // Alamat ditulis eksplisit (bukan dari APP_URL di .env), supaya hasil test
        // tidak berubah-ubah mengikuti isi .env masing-masing komputer.
        $url = 'http://web-arsip.test'
            . route('arsip.qrcode.bulk', ['ids' => [$arsip->id]], false);

        $this->actingAs($admin)
            ->get($url)
            ->assertOk()
            ->assertSee('hanya dikenal oleh komputer ini');
    }

    public function test_tanpa_peringatan_jika_dibuka_lewat_alamat_ip(): void
    {
        $admin = User::factory()->create();
        $arsip = Arsip::factory()->create();

        $url = 'http://192.168.1.168:8000'
            . route('arsip.qrcode.bulk', ['ids' => [$arsip->id]], false);

        $this->actingAs($admin)
            ->get($url)
            ->assertOk()
            ->assertDontSee('hanya dikenal oleh komputer ini')
            ->assertSee('192.168.1.168:8000');
    }
}
