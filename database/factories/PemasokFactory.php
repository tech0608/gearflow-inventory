<?php

namespace Database\Factories;

use App\Models\Pemasok;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemasokFactory extends Factory
{
    protected $model = Pemasok::class;

    public function definition(): array
    {
        return [
            'nama_pemasok' => fake()->company(),
            'kontak'       => fake()->phoneNumber(),
            'alamat'       => fake()->address(),
        ];
    }
}
