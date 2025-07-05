<?php

namespace Database\Factories;

use App\Models\User; // Import Model User
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nasabah>
 */
class NasabahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID'); // Menggunakan Faker Indonesia

        return [
            'user_id' => null, // Akan diisi saat seeder
            'nama_lengkap' => $faker->name(),
            'nik' => $faker->unique()->nik(), // NIK 16 digit Indonesia
            'tempat_lahir' => $faker->city(),
            'tanggal_lahir' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'status_perkawinan' => $faker->randomElement(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati']),
            'pekerjaan' => $faker->jobTitle(),
            'email' => $faker->unique()->safeEmail(),
            'nomor_telepon' => $faker->unique()->phoneNumber(),
            'alamat_ktp' => $faker->address(),
            'alamat_domisili' => $this->faker->boolean(70) ? $faker->address() : null, // 70% kemungkinan beda domisili
            'sumber_dana' => $faker->randomElement(['Gaji', 'Usaha', 'Warisan', 'Investasi']),
            'tujuan_hubungan_bank' => $faker->randomElement(['Tabungan', 'Pembiayaan', 'Investasi', 'ZISWAF']),
            'status_akun' => $faker->randomElement(['Aktif', 'Nonaktif']),
        ];
    }
}
