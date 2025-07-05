<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemSetting>
 */
class SystemSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pindahkan definisi settings ke sini atau gunakan method lain jika terlalu banyak
        // Contoh default settings, bisa lebih banyak dari ini
        $settings = [
            'nama_aplikasi' => ['string', 'Sistem Informasi Bank Syariah'],
            'versi_aplikasi' => ['string', '1.0.0'],
            'format_tanggal' => ['string', 'DD-MM-YYYY'],
            'jam_operasional_mulai' => ['string', '08:00'],
            'jam_operasional_selesai' => ['string', '16:00'],
            'nama_email_pengirim' => ['string', $this->faker->company() . ' Mailer'],
            'api_token_lifetime_minutes' => ['integer', $this->faker->numberBetween(60, 1440)], // 1-24 jam
            'enable_two_factor_auth' => ['boolean', $this->faker->boolean()],
            'default_admin_email' => ['string', $this->faker->safeEmail()],
            'maksimal_transaksi_per_hari' => ['integer', $this->faker->numberBetween(10, 100)],
        ];

        // Ambil kunci secara acak tanpa perlu unique() di sini,
        // karena uniknya akan dijamin oleh constraint database atau seed logic.
        $key = $this->faker->randomElement(array_keys($settings));
        list($tipe, $value) = $settings[$key];

        return [
            'key_setting' => $key,
            'value_setting' => (string) $value, // Cast ke string untuk TEXT kolom
            'tipe_value' => $tipe,
            'deskripsi' => $this->faker->sentence(),
            'updated_by_user_id' => User::factory(), // Akan di-override di seeder
        ];
    }
}
