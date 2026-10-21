<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArsipDigitalFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_filter_arsip_digital_sudah_scan(): void
    {
        $arsipDenganFile = Arsip::factory()->create([
            'kode_berkas'  => 'ARSIP-DIGITAL-01',
            'file_dokumen' => 'test.pdf',
        ]);

        $arsipTanpaFile = Arsip::factory()->create([
            'kode_berkas'  => 'ARSIP-FISIK-01',
            'file_dokumen' => null,
        ]);

        $response = $this->actingAs($this->admin)->get(route('arsip.index', ['digital' => 'sudah']));

        $response->assertOk();
        $response->assertSee('ARSIP-DIGITAL-01');
        $response->assertDontSee('ARSIP-FISIK-01');
    }

    public function test_filter_arsip_digital_hanya_fisik(): void
    {
        $arsipDenganFile = Arsip::factory()->create([
            'kode_berkas'  => 'ARSIP-DIGITAL-02',
            'file_dokumen' => 'test.pdf',
        ]);

        $arsipTanpaFile = Arsip::factory()->create([
            'kode_berkas'  => 'ARSIP-FISIK-02',
            'file_dokumen' => null,
        ]);

        $response = $this->actingAs($this->admin)->get(route('arsip.index', ['digital' => 'belum']));

        $response->assertOk();
        $response->assertSee('ARSIP-FISIK-02');
        $response->assertDontSee('ARSIP-DIGITAL-02');
    }
}

