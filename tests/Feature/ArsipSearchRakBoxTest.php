<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArsipSearchRakBoxTest extends TestCase
{
    use RefreshDatabase;

    public function test_pencarian_rak_menampilkan_berkas_di_rak_tersebut(): void
    {
        $user = User::factory()->create();

        Arsip::factory()->create([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 1,
            'box' => 'Box 01',
            'nama_berkas' => 'Berkas Uji Coba Klaim JHT',
        ]);

        Arsip::factory()->create([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak B',
            'baris' => 1,
            'box' => 'Box 05',
            'nama_berkas' => 'Berkas Di Rak B',
        ]);

        // Cari dengan keyword "Rak A"
        $response = $this->actingAs($user)->get(route('arsip.index', [
            'ruangan' => 'Ruang Arsip Kecil',
            'search' => 'Rak A',
        ]));

        $response->assertOk()
            ->assertSee('Berkas Uji Coba Klaim JHT')
            ->assertDontSee('Berkas Di Rak B');
    }

    public function test_pencarian_box_menampilkan_berkas_di_box_tersebut(): void
    {
        $user = User::factory()->create();

        Arsip::factory()->create([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 3,
            'box' => 'Box 03',
            'nama_berkas' => 'Berkas Di Box Tiga',
        ]);

        // Cari dengan keyword "Box 03"
        $this->actingAs($user)->get(route('arsip.index', ['search' => 'Box 03']))
            ->assertOk()
            ->assertSee('Berkas Di Box Tiga');

        // Cari dengan variasi "Box 3"
        $this->actingAs($user)->get(route('arsip.index', ['search' => 'Box 3']))
            ->assertOk()
            ->assertSee('Berkas Di Box Tiga');
    }

    public function test_katalog_box_bisa_dibuka_tanpa_parameter_baris(): void
    {
        Arsip::factory()->create([
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'baris' => 3,
            'box' => 'Box 03',
            'kode_berkas' => 'BOX-03-TEST',
        ]);

        // Tanpa parameter baris tetap bisa dibuka
        $response = $this->get(route('katalog.box', [
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak A',
            'box' => 'Box 03',
        ]));

        $response->assertOk()
            ->assertSee('BOX-03-TEST')
            ->assertSee('Box 03')
            ->assertDontSee('Box Box 03');
    }
}
