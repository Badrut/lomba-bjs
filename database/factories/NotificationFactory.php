<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Akan di-override di seeder
            'tipe' => $this->faker->randomElement(['Info', 'Peringatan', 'Promosi', 'Transaksi', 'Sistem']),
            'judul' => $this->faker->sentence(3),
            'pesan' => $this->faker->paragraph(2),
            'link' => $this->faker->boolean(50) ? $this->faker->url() : null,
            'is_read' => $this->faker->boolean(70), // 70% kemungkinan sudah dibaca
        ];
    }
}
