<?php

namespace Tests\Feature;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArsipFileDokumenTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->admin = User::factory()->create();
    }

    public function test_unggah_file_dokumen_saat_tambah_arsip(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('arsip.store'), [
            'kode_berkas'    => 'BERKAS-001',
            'nama_berkas'    => 'Uraian Berkas 001',
            'ruangan'        => 'Ruang Arsip Kecil',
            'rak'            => 'Rak 1',
            'baris'          => 1,
            'box'            => 'Box 1',
            'file_dokumen'   => $file,
        ]);

        $response->assertRedirect(route('arsip.index'));
        $response->assertSessionHas('success');

        $arsip = Arsip::where('kode_berkas', 'BERKAS-001')->first();
        $this->assertNotNull($arsip);
        $this->assertNotNull($arsip->file_dokumen);
        $this->assertTrue(Storage::disk('local')->exists('arsip_digital/' . $arsip->file_dokumen));
    }

    public function test_hapus_file_dokumen_lewat_edit(): void
    {
        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');
        $namaFile = 'berkas-002_random123456.pdf';
        Storage::disk('local')->put('arsip_digital/' . $namaFile, 'dummy content');

        $arsip = Arsip::factory()->create([
            'kode_berkas'  => 'BERKAS-002',
            'ruangan'      => 'Ruang Arsip Kecil',
            'baris'        => 1,
            'file_dokumen' => $namaFile,
        ]);

        $response = $this->actingAs($this->admin)->put(route('arsip.update', $arsip), [
            'kode_berkas'  => 'BERKAS-002',
            'nama_berkas'  => $arsip->nama_berkas,
            'ruangan'      => 'Ruang Arsip Kecil',
            'rak'          => $arsip->rak,
            'baris'        => 1,
            'box'          => $arsip->box,
            'hapus_file'   => 1,
        ]);

        $response->assertRedirect(route('arsip.index'));
        $response->assertSessionHas('success');

        $arsip->refresh();
        $this->assertNull($arsip->file_dokumen);
        $this->assertFalse(Storage::disk('local')->exists('arsip_digital/' . $namaFile));
    }

    public function test_akses_file_dokumen_privat_oleh_admin(): void
    {
        $namaFile = 'berkas-003_random123456.pdf';
        Storage::disk('local')->put('arsip_digital/' . $namaFile, 'pdf content');

        $arsip = Arsip::factory()->create([
            'file_dokumen' => $namaFile,
        ]);

        // Tamu (guest) diarahkan ke login
        $this->get(route('arsip.file', $arsip))->assertRedirect(route('login'));

        // Admin yang login bisa mengakses file
        $response = $this->actingAs($this->admin)->get(route('arsip.file', $arsip));
        $response->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_akses_file_dokumen_yang_tidak_ada_menghasilkan_not_found(): void
    {
        $arsip = Arsip::factory()->create([
            'file_dokumen' => 'file-tidak-ada.pdf',
        ]);

        $this->actingAs($this->admin)
            ->get(route('arsip.file', $arsip))
            ->assertNotFound();
    }
}
