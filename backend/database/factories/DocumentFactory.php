<?php

namespace Database\Factories;

use App\Models\Nasabah;
use App\Models\Pembiayaan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisDokumen = $this->faker->randomElement(['KTP', 'NPWP', 'Kartu Keluarga', 'Surat Keterangan Usaha', 'Slip Gaji', 'Akta Jual Beli']);
        $hasNasabah = $this->faker->boolean(80); // 80% terkait nasabah
        $hasPembiayaan = $this->faker->boolean(40); // 40% terkait pembiayaan

        return [
            'nasabah_id' => $hasNasabah ? Nasabah::factory() : null,
            'pembiayaan_id' => $hasPembiayaan ? Pembiayaan::factory() : null,
            'jenis_dokumen' => $jenisDokumen,
            'nama_file_asli' => $this->faker->word() . '.' . $this->faker->randomElement(['pdf', 'jpg', 'png']),
            'path_file' => 'documents/' . $this->faker->uuid() . '.' . $this->faker->randomElement(['pdf', 'jpg', 'png']),
            'mime_type' => $this->faker->randomElement(['application/pdf', 'image/jpeg', 'image/png']),
            'ukuran_file_bytes' => $this->faker->numberBetween(10240, 5120000), // 10KB - 5MB
            'diupload_oleh_user_id' => User::factory(), // Akan di-override di seeder
        ];
    }
}
