<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['kode_barang', 'nama_barang', 'kategori', 'stok', 'stok_minimum', 'harga_satuan'];

    protected $casts = [
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
        'harga_satuan' => 'decimal:2',
    ];

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang');
    }

    public function barangKeluars()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang');
    }

    public function pemasoks()
    {
        return $this->belongsToMany(Pemasok::class, 'barang_masuks', 'id_barang', 'id_pemasok')
                    ->withPivot('id', 'tanggal_masuk', 'jumlah_masuk')
                    ->withTimestamps();
    }

    public function getNilaiStokAttribute(): float
    {
        return $this->stok * $this->harga_satuan;
    }

    public function getStatusStokAttribute(): string
    {
        $min = $this->stok_minimum ?: 5;
        if ($this->stok <= $min)  return 'kritis';
        if ($this->stok <= $min * 2) return 'rendah';
        return 'aman';
    }
}
