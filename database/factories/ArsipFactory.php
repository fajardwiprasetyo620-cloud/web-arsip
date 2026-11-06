<?php

namespace Database\Factories;

use App\Models\Arsip;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Arsip>
 */
class ArsipFactory extends Factory
{
    protected $model = Arsip::class;

    public function definition(): array
    {
        return [
            'kode_berkas' => 'TEST-'.$this->faker->unique()->numerify('######'),
            'nama_berkas' => 'Berkas Uji '.$this->faker->words(3, true),
            'ruangan' => 'Ruang Arsip Kecil',
            'rak' => 'Rak '.$this->faker->randomLetter(),
            'baris' => $this->faker->numberBetween(1, 4),
            'box' => (string) $this->faker->numberBetween(1, 20),
            'status' => 'Tersedia',
        ];
    }

    public function dipinjam(): static
    {
        return $this->state(fn () => ['status' => 'Dipinjam']);
    }
}
