<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DatabaseBackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_mengunduh_backup_database(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get(route('database.backup'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/sql');
        $files = Storage::disk('local')->files('backups');
        $this->assertCount(1, $files);
        $this->assertMatchesRegularExpression(
            '/^backups\/backup-e-arsip-\d{4}-\d{2}-\d{2}_\d{6}_\d{6}\.sql$/',
            $files[0]
        );
        $contents = Storage::disk('local')->get($files[0]);
        $this->assertStringContainsString('-- Selesai pada:', $contents);
        $this->assertSame($files, array_values(array_filter($files, fn ($file) => ! str_ends_with($file, '.tmp'))));

        $restoredDatabase = new \PDO('sqlite::memory:');
        $restoredDatabase->exec($contents);
        $this->assertSame(1, (int) $restoredDatabase->query('SELECT COUNT(*) FROM users')->fetchColumn());
    }

    public function test_tamu_tidak_bisa_mengunduh_backup_database(): void
    {
        $response = $this->get(route('database.backup'));

        $response->assertRedirect(route('login'));
    }

    public function test_retensi_hanya_menghapus_backup_aplikasi_yang_sudah_kedaluwarsa(): void
    {
        Storage::fake('local');
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $backupLama = 'backups/backup-e-arsip-2020-01-01_000000.sql';
        $fileLain = 'backups/cadangan-manual.sql';

        $disk->put($backupLama, 'backup lama');
        $disk->put($fileLain, 'file yang bukan backup aplikasi');
        touch($disk->path($backupLama), now()->subDays(31)->getTimestamp());
        touch($disk->path($fileLain), now()->subDays(31)->getTimestamp());

        $dihapus = app(DatabaseBackupService::class)->cleanOldBackups();

        $this->assertSame(1, $dihapus);
        $disk->assertMissing($backupLama);
        $disk->assertExists($fileLain);
    }
}
