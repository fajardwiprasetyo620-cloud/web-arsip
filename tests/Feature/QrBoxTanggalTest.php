<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji tanggal berkas pada halaman Isi Box dan pada stiker QR Box.
 */
class QrBoxTanggalTest extends TestCase
{
    use RefreshDatabase;

    private array $lokasi = [
        'ruangan' => 'Ruang Arsip Kecil',
        'rak'     => 'A',
        'baris'   => 3,
        'box'     => '3',
    ];

    private function arsipDi(string $kode, ?string $tanggal, array $extra = []): Arsip
    {
        return Arsip::factory()->create(array_merge($this->lokasi, [
            'kode_berkas'    => $kode,
            'tanggal_berkas' => $tanggal,
        ], $extra));
    }

    public function test_isi_box_diurutkan_dari_tanggal_terlama_bukan_dari_kode(): void
    {
        // Kode "ZZ" bertanggal lebih awal, jadi harus tampil lebih dulu daripada "AA"
        $this->arsipDi('ZZ-001', '2026-10-01');
        $this->arsipDi('AA-002', '2026-10-09');

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertSeeInOrder(['ZZ-001', 'AA-002'])
            ->assertSee('01/10/2026')
            ->assertSee('09/10/2026');
    }

    public function test_berkas_tanpa_tanggal_tampil_paling_bawah(): void
    {
        $this->arsipDi('AAA-TANPA-TANGGAL', null);
        $this->arsipDi('ZZZ-BERTANGGAL', '2026-10-05');

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertSeeInOrder(['ZZZ-BERTANGGAL', 'AAA-TANPA-TANGGAL']);
    }

    public function test_isi_box_menampilkan_periode_awal_sampai_akhir(): void
    {
        $this->arsipDi('PER-001', '2026-10-03');
        $this->arsipDi('PER-002', '2026-10-28');
        $this->arsipDi('PER-003', null); // tanpa tanggal tidak mempengaruhi rentang

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertSee('Periode berkas')
            ->assertSee('03/10/2026')
            ->assertSee('28/10/2026');
    }

    public function test_isi_box_tanpa_satu_pun_tanggal_tidak_menampilkan_periode(): void
    {
        $this->arsipDi('NOL-001', null);
        $this->arsipDi('NOL-002', null);

        $this->get(route('katalog.box', $this->lokasi))
            ->assertOk()
            ->assertDontSee('Periode berkas');
    }

    public function test_stiker_box_memuat_tanggal_awal_dan_akhir(): void
    {
        $admin = User::factory()->create();
        $this->arsipDi('STK-001', '2026-10-01');
        $this->arsipDi('STK-002', '2026-10-28');
        $this->arsipDi('STK-003', null);

        $response = $this->actingAs($admin)
            ->get(route('arsip.qrcode.box', ['semua' => 1]));

        $response->assertOk()
            ->assertSee('Periode berkas')
            ->assertSee('01/10/2026')
            ->assertSee('28/10/2026');

        $box = $response->viewData('boxes')->first();
        $this->assertEquals('2026-10-01', $box->tanggal_awal);
        $this->assertEquals('2026-10-28', $box->tanggal_akhir);
    }

    public function test_stiker_box_tanpa_tanggal_tidak_menampilkan_periode(): void
    {
        $admin = User::factory()->create();
        $this->arsipDi('STK-NOL-001', null);

        $this->actingAs($admin)
            ->get(route('arsip.qrcode.box', ['semua' => 1]))
            ->assertOk()
            ->assertDontSee('Periode berkas');
    }
}
