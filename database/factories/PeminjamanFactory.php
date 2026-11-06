<?php

namespace Database\Factories;

use App\Models\Arsip;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    protected $model = Peminjaman::class;

    public function definition(): array
    {
        return [
            'arsip_id' => Arsip::factory(),
            'nama_peminjam' => $this->faker->name(),
            'divisi' => 'Kepesertaan',
            'tanggal_pinjam' => now()->toDateString(),
            'tenggat_waktu' => now()->addDays(7)->toDateString(),
            'status' => 'Dipinjam',
        ];
    }

    public function dikembalikan(): static
    {
        return $this->state(fn () => [
            'status' => 'Dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
            'disetujui_oleh' => 'Admin Uji',
        ]);
    }

    public function terlambat(): static
    {
        return $this->state(fn () => [
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tenggat_waktu' => now()->subDays(3)->toDateString(),
        ]);
    }
}
