<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $fillable = ['nama_pemasok', 'kontak', 'alamat'];

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_pemasok');
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_masuks', 'id_pemasok', 'id_barang')
                    ->withPivot('id', 'tanggal_masuk', 'jumlah_masuk')
                    ->withTimestamps();
    }
}
