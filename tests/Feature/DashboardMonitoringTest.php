<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_dashboard_menampilkan_banner_keterlambatan_dan_monitoring_rak(): void
    {
        $arsip = Arsip::factory()->create([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => 'Box 01',
        ]);

        Peminjaman::factory()->for($arsip, 'arsip')->terlambat()->create([
            'nama_peminjam' => 'Dini Anggraini',
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Perhatian: Ada 1 Peminjaman Melewati Batas Waktu!');
        $response->assertSee('Monitoring Kapasitas');
        $response->assertSee('Ruang Arsip Kecil');
    }

    public function test_navigasi_memuat_badge_jumlah_terlambat(): void
    {
        $arsip = Arsip::factory()->create();
        Peminjaman::factory()->for($arsip, 'arsip')->terlambat()->create();

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('title="1 peminjaman terlambat"', false);
    }
}
