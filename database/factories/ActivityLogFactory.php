<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $activityType = $this->faker->randomElement(['Login', 'Buka Rekening', 'Ajukan Pembiayaan', 'Update Profil', 'Catat ZISWAF', 'Logout', 'Ubah Password']);
        $dataLama = null;
        $dataBaru = null;

        if (in_array($activityType, ['Update Profil', 'Ubah Password'])) {
            $dataLama = ['old_value' => $this->faker->word()];
            $dataBaru = ['new_value' => $this->faker->word()];
        }

        return [
            'user_id' => User::factory(), // Akan di-override di seeder
            'tipe_aktivitas' => $activityType,
            'deskripsi' => $this->faker->sentence(),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'data_lama_json' => $dataLama,
            'data_baru_json' => $dataBaru,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
