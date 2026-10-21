<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupService
{
    /**
     * Generate SQL dump of the database and save it to storage/app/backups.
     * Returns the full path to the generated backup file.
     */
    public function backup(): string
    {
        $driver = DB::getDriverName();
        $sql = "-- =====================================================\n";
        $sql .= "-- E-Arsip BPJS Ketenagakerjaan - Database Backup\n";
        $sql .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Driver: {$driver}\n";
        $sql .= "-- =====================================================\n\n";

        if ($driver === 'sqlite') {
            $tableRows = DB::select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($tableRows as $t) {
                $tableName = $t->name;
                $sql .= "-- -----------------------------------------------------\n";
                $sql .= "-- Tabel `{$tableName}`\n";
                $sql .= "-- -----------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $t->sql . ";\n\n";

                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $values = [];
                    foreach ((array) $row as $val) {
                        if ($val === null) {
                            $values[] = 'NULL';
                        } elseif (is_numeric($val) && !str_starts_with((string)$val, '0') && !preg_match('/[^0-9]/', (string)$val)) {
                            $values[] = $val;
                        } else {
                            $escaped = str_replace("'", "''", (string) $val);
                            $values[] = "'{$escaped}'";
                        }
                    }
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        } else {
            // MySQL Driver
            $database = config('database.connections.mysql.database');
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $database;

            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

            foreach ($tables as $t) {
                $tableName = $t->$tableKey ?? array_values((array) $t)[0];

                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createSql = $createTable[0]->{'Create Table'} ?? '';

                $sql .= "-- -----------------------------------------------------\n";
                $sql .= "-- Struktur Tabel `{$tableName}`\n";
                $sql .= "-- -----------------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createSql . ";\n\n";

                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Data Tabel `{$tableName}` ({$rows->count()} baris)\n";
                    foreach ($rows->chunk(50) as $chunk) {
                        $insertSql = "INSERT INTO `{$tableName}` VALUES ";
                        $valueStrings = [];

                        foreach ($chunk as $row) {
                            $values = [];
                            foreach ((array) $row as $val) {
                                if ($val === null) {
                                    $values[] = 'NULL';
                                } elseif (is_numeric($val) && !str_starts_with((string)$val, '0') && !preg_match('/[^0-9]/', (string)$val)) {
                                    $values[] = $val;
                                } else {
                                    $escaped = str_replace(
                                        ["\\", "\x00", "\n", "\r", "'", '"', "\x1a"],
                                        ["\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z"],
                                        (string) $val
                                    );
                                    $values[] = "'{$escaped}'";
                                }
                            }
                            $valueStrings[] = "(" . implode(', ', $values) . ")";
                        }

                        $insertSql .= implode(",\n", $valueStrings) . ";\n";
                        $sql .= $insertSql;
                    }
                    $sql .= "\n";
                }
            }
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        }

        $sql .= "-- Selesai pada: " . now()->format('Y-m-d H:i:s') . "\n";

        $fileName = 'backup-e-arsip-' . now()->format('Y-m-d_His') . '.sql';
        Storage::disk('local')->put('backups/' . $fileName, $sql);

        return Storage::disk('local')->path('backups/' . $fileName);
    }
}

