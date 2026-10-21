<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DatabaseBackupService
{
    private const BACKUP_PATTERN = '/^backup-e-arsip-\d{4}-\d{2}-\d{2}_\d{6}(?:_\d{6})?\.sql$/';

    /**
     * Generate a database dump using bounded-memory reads and atomic file publishing.
     */
    public function backup(): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');

        $fileName = 'backup-e-arsip-'.now()->format('Y-m-d_His_u').'.sql';
        $relativeFilePath = 'backups/'.$fileName;
        $absoluteFilePath = $disk->path($relativeFilePath);
        $temporaryFilePath = tempnam($disk->path('backups'), '.backup-');

        if ($temporaryFilePath === false) {
            throw new RuntimeException('Gagal membuat file sementara untuk backup database.');
        }

        $handle = fopen($temporaryFilePath, 'wb');
        if ($handle === false) {
            $this->hapusFileSementara($temporaryFilePath);
            throw new RuntimeException('Gagal membuka file sementara untuk backup database.');
        }

        $published = false;

        try {
            $driver = DB::getDriverName();

            $this->write($handle, "-- =====================================================\n");
            $this->write($handle, "-- E-Arsip BPJS Ketenagakerjaan - Database Backup\n");
            $this->write($handle, '-- Generated: '.now()->format('Y-m-d H:i:s')."\n");
            $this->write($handle, "-- Driver: {$driver}\n");
            $this->write($handle, "-- =====================================================\n\n");

            if ($driver === 'sqlite') {
                $this->writeSqliteDump($handle);
            } elseif ($driver === 'mysql') {
                $this->writeMysqlDump($handle);
            } else {
                throw new RuntimeException("Driver database '{$driver}' belum didukung untuk backup.");
            }

            $this->write($handle, '-- Selesai pada: '.now()->format('Y-m-d H:i:s')."\n");

            if (! fflush($handle)) {
                throw new RuntimeException('Gagal menyelesaikan penulisan file backup.');
            }

            if (! fclose($handle)) {
                $handle = false;
                throw new RuntimeException('Gagal menutup file backup.');
            }
            $handle = false;

            if (file_exists($absoluteFilePath) || ! rename($temporaryFilePath, $absoluteFilePath)) {
                throw new RuntimeException('Gagal menerbitkan file backup yang sudah selesai.');
            }

            $published = true;
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }

            if (! $published && is_file($temporaryFilePath)) {
                $this->hapusFileSementara($temporaryFilePath);
            }
        }

        $this->cleanOldBackups(10, 30);

        return $absoluteFilePath;
    }

    private function hapusFileSementara(string $path): void
    {
        if (is_file($path) && ! @unlink($path)) {
            Log::warning('File sementara backup gagal dihapus.', ['path' => $path]);
        }
    }

    /** @param resource $handle */
    private function writeSqliteDump($handle): void
    {
        $tables = DB::select(
            "SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
        );

        foreach ($tables as $table) {
            $tableName = $table->name;
            $this->write($handle, "-- -----------------------------------------------------\n");
            $this->write($handle, "-- Tabel `{$tableName}`\n");
            $this->write($handle, "-- -----------------------------------------------------\n");
            $this->write($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            $this->write($handle, $table->sql.";\n\n");

            DB::table($tableName)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($handle, $tableName) {
                foreach ($rows as $row) {
                    $values = array_map(
                        fn ($value) => $this->quoteSqliteValue($value),
                        (array) $row
                    );
                    $this->write(
                        $handle,
                        "INSERT INTO `{$tableName}` VALUES (".implode(', ', $values).");\n"
                    );
                }
            });

            $this->write($handle, "\n");
        }
    }

    /** @param resource $handle */
    private function writeMysqlDump($handle): void
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_'.$database;

        $this->write($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        $this->write($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

        foreach ($tables as $table) {
            $tableName = $table->$tableKey ?? array_values((array) $table)[0];
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $createSql = $createTable[0]->{'Create Table'} ?? '';

            $this->write($handle, "-- -----------------------------------------------------\n");
            $this->write($handle, "-- Struktur Tabel `{$tableName}`\n");
            $this->write($handle, "-- -----------------------------------------------------\n");
            $this->write($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            $this->write($handle, $createSql.";\n\n");

            DB::table($tableName)->orderBy(DB::raw('1'))->chunk(100, function ($rows) use ($handle, $tableName) {
                $valueStrings = [];

                foreach ($rows as $row) {
                    $values = array_map(
                        fn ($value) => $this->quoteMysqlValue($value),
                        (array) $row
                    );
                    $valueStrings[] = '('.implode(', ', $values).')';
                }

                if ($valueStrings !== []) {
                    $this->write(
                        $handle,
                        "INSERT INTO `{$tableName}` VALUES ".implode(",\n", $valueStrings).";\n"
                    );
                }
            });

            $this->write($handle, "\n");
        }

        $this->write($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
    }

    private function quoteSqliteValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_numeric($value) && ! str_starts_with((string) $value, '0') && ! preg_match('/[^0-9]/', (string) $value)) {
            return (string) $value;
        }

        return "'".str_replace("'", "''", (string) $value)."'";
    }

    private function quoteMysqlValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_numeric($value) && ! str_starts_with((string) $value, '0') && ! preg_match('/[^0-9]/', (string) $value)) {
            return (string) $value;
        }

        $escaped = str_replace(
            ['\\', "\x00", "\n", "\r", "'", '"', "\x1a"],
            ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'],
            (string) $value
        );

        return "'{$escaped}'";
    }

    /** @param resource $handle */
    private function write($handle, string $contents): void
    {
        $length = strlen($contents);
        $written = 0;

        while ($written < $length) {
            $bytes = fwrite($handle, substr($contents, $written));

            if ($bytes === false || $bytes === 0) {
                throw new RuntimeException('Penulisan file backup terhenti sebelum selesai.');
            }

            $written += $bytes;
        }
    }

    /**
     * Remove only application-generated SQL backups, retaining the newest files and recent backups.
     */
    public function cleanOldBackups(int $maxFiles = 10, int $maxDays = 30): int
    {
        if ($maxFiles < 0 || $maxDays < 0) {
            throw new \InvalidArgumentException('Batas retensi backup tidak boleh bernilai negatif.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        if (! $disk->exists('backups')) {
            return 0;
        }

        $files = array_values(array_filter(
            $disk->files('backups'),
            fn ($file) => preg_match(self::BACKUP_PATTERN, basename($file)) === 1
        ));

        usort($files, function ($first, $second) use ($disk) {
            $modifiedOrder = $disk->lastModified($second) <=> $disk->lastModified($first);

            return $modifiedOrder !== 0
                ? $modifiedOrder
                : strcmp(basename($second), basename($first));
        });

        $cutoffTime = now()->subDays($maxDays)->getTimestamp();
        $deleted = 0;

        foreach ($files as $index => $file) {
            if ($index >= $maxFiles || $disk->lastModified($file) < $cutoffTime) {
                if (! $disk->delete($file)) {
                    throw new RuntimeException("Gagal menghapus backup lama: {$file}");
                }
                $deleted++;
            }
        }

        return $deleted;
    }
}
