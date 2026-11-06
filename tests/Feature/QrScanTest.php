<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_scanner_qr_bisa_dibuka_oleh_publik(): void
    {
        $response = $this->get(route('qr.scan'));

        $response->assertOk();
        $response->assertSee('Pemindai QR Code');
    }

    public function test_halaman_scanner_qr_menampilkan_layout_admin_saat_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('qr.scan'));

        $response->assertOk();
        $response->assertSee('Pemindai QR Code');
        $response->assertSee($user->name);
        $response->assertSee('Daftar Arsip');
        $response->assertSee('Siap Memindai QR Code');
    }
}
