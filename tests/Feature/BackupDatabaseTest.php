<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_mengunduh_backup_database(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('database.backup'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/sql');
    }

    public function test_tamu_tidak_bisa_mengunduh_backup_database(): void
    {
        $response = $this->get(route('database.backup'));

        $response->assertRedirect(route('login'));
    }
}

