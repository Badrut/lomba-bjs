<?php

namespace Database\Factories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembiayaan>
 */
class PembiayaanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisPembiayaan = $this->faker->randomElement(['Murabahah', 'Musyarakah', 'Mudharabah', 'Ijarah Multiguna']);
        $akad = '';
        $marginKeuntungan = null;

        switch ($jenisPembiayaan) {
            case 'Murabahah':
                $akad = 'Murabahah';
                $marginKeuntungan = $this->faker->randomFloat(2, 500000, 5000000); // Margin 500rb - 5 juta
                break;
            case 'Musyarakah':
                $akad = 'Musyarakah';
                break;
            case 'Mudharabah':
                $akad = 'Mudharabah';
                break;
            case 'Ijarah Multiguna':
                $akad = 'Ijarah';
                $marginKeuntungan = $this->faker->randomFloat(2, 300000, 3000000);
                break;
        }

        $tanggalPengajuan = $this->faker->dateTimeBetween('-2 years', 'now');
        $tenor = $this->faker->randomElement([12, 24, 36, 48, 60]); // Tenor dalam bulan
        $tanggalPencairan = $this->faker->boolean(80) ? $this->faker->dateTimeBetween($tanggalPengajuan, 'now') : null;
        $status = 'Diajukan';
        if ($tanggalPencairan) {
            $status = $this->faker->randomElement(['Aktif', 'Lunas', 'Bermasalah']);
        } elseif ($this->faker->boolean(10)) { // 10% kemungkinan ditolak
            $status = 'Ditolak';
        } else {
            $status = 'Disetujui'; // Default setelah diajukan
        }

        $tanggalJatuhTempoPertama = null;
        $tanggalJatuhTempoAkhir = null;
        if ($tanggalPencairan) {
            $tanggalJatuhTempoPertama = (clone $tanggalPencairan)->modify('+1 month')->format('Y-m-d');
            $tanggalJatuhTempoAkhir = (clone $tanggalPencairan)->modify('+' . $tenor . ' months')->format('Y-m-d');
        }

        return [
            'nasabah_id' => Nasabah::factory(),
            'jenis_pembiayaan' => $jenisPembiayaan,
            'akad' => $akad,
            'jumlah_pokok' => $this->faker->randomFloat(2, 1000000, 200000000), // 1 juta - 200 juta
            'margin_keuntungan' => $marginKeuntungan,
            'tenor_bulan' => $tenor,
            'tanggal_pengajuan' => $tanggalPengajuan->format('Y-m-d'),
            'tanggal_pencairan' => $tanggalPencairan ? $tanggalPencairan->format('Y-m-d') : null,
            'tanggal_jatuh_tempo_pertama' => $tanggalJatuhTempoPertama,
            'tanggal_jatuh_tempo_akhir' => $tanggalJatuhTempoAkhir,
            'tujuan_pembiayaan' => $this->faker->sentence(),
            'status' => $status,
        ];
    }
}
