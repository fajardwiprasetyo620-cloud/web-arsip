<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Registrasi publik SENGAJA dimatikan pada perbaikan Tahap 1 (celah keamanan:
 * sebelumnya siapa pun bisa membuat akun admin lewat /register). Test ini
 * membuktikan bahwa penonaktifannya benar-benar berfungsi.
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_registrasi_tidak_bisa_diakses(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_endpoint_registrasi_tidak_memproses_pendaftaran(): void
    {
        $response = $this->post('/register', [
            'name' => 'Percobaan Registrasi',
            'email' => 'percobaan.registrasi@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'percobaan.registrasi@test.com']);
    }
}
