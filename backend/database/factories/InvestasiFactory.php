<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Investasi>
 */
class InvestasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisInvestasi = $this->faker->randomElement(['Sukuk Ritel', 'Reksa Dana Syariah', 'Saham Syariah']);
        $akad = $this->faker->randomElement(['Ijarah', 'Mudharabah', 'Musyarakah']);
        $jangkaWaktu = $this->faker->randomElement(['1 Tahun', '3 Bulan', '6 Bulan', 'Tidak Terbatas']);
        $tanggalMulai = $this->faker->dateTimeBetween('-3 years', 'now');
        $tanggalJatuhTempo = ($jangkaWaktu !== 'Tidak Terbatas') ? $this->faker->dateTimeBetween($tanggalMulai, '+' . str_replace([' Tahun', ' Bulan'], [' years', ' months'], $jangkaWaktu)) : null;
        $status = $this->faker->randomElement(['Aktif', 'Selesai']);

        return [
            'nasabah_id' => Nasabah::factory(),
            'jenis_investasi' => $jenisInvestasi,
            'akad' => $akad,
            'nilai_investasi_pokok' => $this->faker->randomFloat(2, 500000, 100000000), // 500rb - 100 juta
            'nilai_saat_ini' => $this->faker->randomFloat(2, 500000, 150000000), // Bisa lebih besar dari pokok
            'jangka_waktu_investasi' => $jangkaWaktu,
            'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
            'tanggal_jatuh_tempo' => $tanggalJatuhTempo ? $tanggalJatuhTempo->format('Y-m-d') : null,
            'nisbah_imbal_hasil_nasabah' => $this->faker->numberBetween(5, 15),
            'status' => $status,
        ];
    }
}
