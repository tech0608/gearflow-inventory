<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = ['id_barang', 'id_pemasok', 'tanggal_masuk', 'jumlah_masuk'];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah_masuk'  => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'id_pemasok');
    }
}
