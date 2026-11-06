<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PindahkanFileArsip extends Command
{
    protected $signature = 'arsip:pindah-file';

    protected $description = 'Pindahkan file dokumen arsip lama dari penyimpanan publik ke penyimpanan privat';

    public function handle(): int
    {
        $publik = Storage::disk('public');
        $privat = Storage::disk('local');
        $jumlah = 0;

        foreach ($publik->files('arsip_digital') as $path) {
            if ($privat->exists($path)) {
                $this->warn("Dilewati (sudah ada di penyimpanan privat): {$path}");

                continue;
            }

            $privat->put($path, $publik->get($path));
            $publik->delete($path);
            $jumlah++;
        }

        $this->info("{$jumlah} file dipindahkan ke penyimpanan privat.");

        return self::SUCCESS;
    }
}
