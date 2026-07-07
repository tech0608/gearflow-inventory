<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'nama_pengguna' => fake()->name(),
            'username'      => fake()->unique()->userName(),
            'password'      => Hash::make('password123'),
            'role'          => fake()->randomElement(['admin', 'staf']),
        ];
    }
}
