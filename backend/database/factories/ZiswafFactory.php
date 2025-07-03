<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ziswaf>
 */
class ZiswafFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisZiswaf = $this->faker->randomElement(['Zakat', 'Infaq', 'Sedekah', 'Wakaf']);
        $isNasabah = $this->faker->boolean(70); // 70% kemungkinan donatur adalah nasabah

        return [
            'nasabah_id' => $isNasabah ? Nasabah::factory() : null,
            'nama_donatur' => $isNasabah ? null : $this->faker->name(),
            'jenis_ziswaf' => $jenisZiswaf,
            'nominal' => $this->faker->randomFloat(2, 10000, 10000000), // 10rb - 10 juta
            'tanggal_penerimaan' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'metode_penerimaan' => $this->faker->randomElement(['Tunai', 'Transfer Bank', 'Potong Rekening']),
            'tujuan_penyaluran' => $this->faker->sentence(),
            'status_penyaluran' => $this->faker->randomElement(['Tercatat', 'Tersalurkan']),
        ];
    }
}
