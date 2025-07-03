<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rekening>
 */
class RekeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisRekening = $this->faker->randomElement(['Tabungan Wadiah', 'Tabungan Mudharabah', 'Deposito Mudharabah', 'Simpanan Haji & Umrah']);
        $akad = '';
        $jangkaWaktu = null;
        $tglJatuhTempo = null;
        $nisbahNasabah = null;

        switch ($jenisRekening) {
            case 'Tabungan Wadiah':
                $akad = 'Wadiah Yad Dhamanah';
                break;
            case 'Tabungan Mudharabah':
                $akad = 'Mudharabah Mutlaqah';
                $nisbahNasabah = $this->faker->numberBetween(50, 70); // Misal 50-70%
                break;
            case 'Deposito Mudharabah':
                $akad = 'Mudharabah Muqayyadah';
                $jangkaWaktu = $this->faker->randomElement([1, 3, 6, 12, 24]);
                $tglJatuhTempo = $this->faker->dateTimeBetween('+' . $jangkaWaktu . ' months', '+' . ($jangkaWaktu + 1) . ' months')->format('Y-m-d');
                $nisbahNasabah = $this->faker->numberBetween(60, 80); // Misal 60-80%
                break;
            case 'Simpanan Haji & Umrah':
                $akad = 'Wadiah Yad Dhamanah';
                break;
        }

        $tanggalBuka = $this->faker->dateTimeBetween('-5 years', 'now');

        return [
            'nasabah_id' => Nasabah::factory(), // Akan di-override di seeder jika sudah punya Nasabah
            'nomor_rekening' => $this->faker->unique()->numerify('##########'), // 10 digit nomor rekening
            'jenis_rekening' => $jenisRekening,
            'akad' => $akad,
            'saldo' => $this->faker->randomFloat(2, 100000, 50000000), // Saldo antara 100rb - 50 juta
            'status' => $this->faker->randomElement(['Aktif', 'Beku']),
            'tanggal_buka' => $tanggalBuka->format('Y-m-d'),
            'waktu_buka' => $tanggalBuka->format('H:i:s'),
            'jangka_waktu_bulan' => $jangkaWaktu,
            'tanggal_jatuh_tempo_berjangka' => $tglJatuhTempo,
            'nisbah_nasabah_persen' => $nisbahNasabah,
        ];
    }
}
