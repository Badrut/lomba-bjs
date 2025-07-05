<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NisbahSetting>
 */
class NisbahSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            'Tabungan Mudharabah' => ['Mudharabah', $this->faker->numberBetween(50, 70)],
            'Deposito 1 Bulan' => ['Mudharabah', $this->faker->numberBetween(70, 80)],
            'Deposito 3 Bulan' => ['Mudharabah', $this->faker->numberBetween(75, 85)],
            'Deposito 6 Bulan' => ['Mudharabah', $this->faker->numberBetween(80, 90)],
            'Deposito 12 Bulan' => ['Mudharabah', $this->faker->numberBetween(85, 95)],
            'Pembiayaan Murabahah' => ['Murabahah', 0], // Nisbah 0 untuk Murabahah karena berbasis margin
            'Pembiayaan Musyarakah' => ['Musyarakah', $this->faker->numberBetween(40, 60)],
        ];

        $productKey = $this->faker->randomElement(array_keys($products));
        list($jenisAkad, $nisbahNasabah) = $products[$productKey];
        $nisbahBank = 100 - $nisbahNasabah;

        return [
            'nama_produk' => $productKey,
            'jenis_akad' => $jenisAkad,
            'tahun_berlaku' => $this->faker->year(),
            'bulan_berlaku' => str_pad($this->faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT),
            'nisbah_nasabah_persen' => $nisbahNasabah,
            'nisbah_bank_persen' => $nisbahBank,
            'keterangan' => $this->faker->sentence(),
        ];
    }
}
