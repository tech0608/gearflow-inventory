<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        $kategori = fake()->randomElement(['Suku Cadang', 'Alat Kerja', 'Bahan Habis Pakai']);
        $prefix = match($kategori) {
            'Suku Cadang' => 'SPC',
            'Alat Kerja' => 'TLS',
            'Bahan Habis Pakai' => 'BHP',
        };

        return [
            'kode_barang'  => $prefix . '-' . fake()->unique()->numerify('#####'),
            'nama_barang'  => fake()->words(3, true),
            'kategori'     => $kategori,
            'stok'         => fake()->numberBetween(5, 100),
            'stok_minimum' => fake()->numberBetween(2, 10),
            'harga_satuan' => fake()->randomFloat(2, 10000, 500000),
        ];
    }
}
