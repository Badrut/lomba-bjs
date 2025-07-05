<?php

namespace Database\Factories;

use App\Models\Nasabah;
use App\Models\Pembiayaan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancingCard>
 */
class FinancingCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $limit = $this->faker->randomFloat(2, 5000000, 50000000); // 5 juta - 50 juta
        $limitTersedia = $this->faker->randomFloat(2, 0, $limit);
        $tanggalKadaluarsa = $this->faker->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d');
        $cardType = $this->faker->randomElement(['Amanah Gold', 'Amanah Silver', 'Amanah Platinum']);

        return [
            'nasabah_id' => Nasabah::factory(),
            'pembiayaan_id' => Pembiayaan::factory(), // Akan di-override di seeder
            'nomor_kartu' => $this->faker->unique()->creditCardNumber(),
            'nama_pada_kartu' => $this->faker->name(),
            'jenis_kartu' => $cardType,
            'tanggal_kadaluarsa' => $tanggalKadaluarsa,
            'cvv' => $this->faker->numerify('###'),
            'total_limit' => $limit,
            'limit_tersedia' => $limitTersedia,
            'tanggal_cetak_tagihan' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status_kartu' => $this->faker->randomElement(['Aktif', 'Blokir', 'Nonaktif']),
        ];
    }
}
