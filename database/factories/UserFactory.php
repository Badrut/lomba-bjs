<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Untuk peran nasabah, nama_lengkap akan diambil dari NasabahFactory
        // Untuk peran admin/teller, bisa pakai nama acak
        $role = $this->faker->randomElement(['nasabah', 'admin_keuangan', 'admin_cs', 'teller', 'manager', 'auditor']);
        $name = ($role === 'nasabah') ? null : $this->faker->name(); // Nama untuk non-nasabah user

        return [
            'nama' => $name, // Akan di-override jika role nasabah
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'), // default password: 'password'
            'role' => $role,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the factory to set specific role.
     */
    public function role(string $role): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => $role,
            'nama' => $this->faker->name(), // Pastikan nama diisi untuk non-nasabah
        ]);
    }

    /**
     * Configure the factory for a Nasabah role.
     */
    public function forNasabah(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'nasabah',
            'nama' => null, // Nama akan diisi dari Nasabah
        ]);
    }
}
