<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'arsip:backup';
    protected $description = 'Cadangkan database sistem E-Arsip ke berkas SQL di storage/app/backups';

    public function handle(DatabaseBackupService $backupService): int
    {
        $this->info('Memulai pencadangan database E-Arsip...');

        try {
            $path = $backupService->backup();
            $this->info("Pencadangan berhasil! Berkas tersimpan di: {$path}");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Gagal mencadangkan database: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

