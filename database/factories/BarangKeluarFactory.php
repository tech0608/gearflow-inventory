<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangKeluarFactory extends Factory
{
    protected $model = BarangKeluar::class;

    public function definition(): array
    {
        return [
            'id_barang'      => Barang::factory(),
            'tanggal_keluar' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jumlah_keluar'  => fake()->numberBetween(1, 5),
            'tujuan'         => fake()->randomElement([
                'Perbaikan Motor Honda Vario',
                'Ganti Ban Yamaha Mio',
                'Servis Rutin Suzuki Satria',
                'Perbaikan Rem Kawasaki Ninja',
                'Penjualan Suku Cadang Retail'
            ]),
        ];
    }
}
