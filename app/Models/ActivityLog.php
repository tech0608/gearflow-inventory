<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'nama_user',
        'action',
        'description',
        'ip_address',
    ];

    /**
     * Helper untuk mencatat log aktivitas sistem secara simpel.
     */
    public static function record(string $action, string $description): void
    {
        try {
            $user = session('pengguna');
            self::create([
                'user_id'     => $user ? $user['id'] : null,
                'nama_user'   => $user ? $user['nama'] : 'Sistem / Tamu',
                'action'      => strtoupper($action),
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Jangan hentikan eksekusi utama jika gagal catat log
        }
    }
}
