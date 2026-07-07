<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = ['id_barang', 'tanggal_keluar', 'jumlah_keluar', 'tujuan'];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'jumlah_keluar'  => 'integer',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
