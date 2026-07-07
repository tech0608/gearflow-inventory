<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pemasok;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangMasukFactory extends Factory
{
    protected $model = BarangMasuk::class;

    public function definition(): array
    {
        return [
            'id_barang'     => Barang::factory(),
            'id_pemasok'    => Pemasok::factory(),
            'tanggal_masuk' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jumlah_masuk'  => fake()->numberBetween(1, 50),
        ];
    }
}
