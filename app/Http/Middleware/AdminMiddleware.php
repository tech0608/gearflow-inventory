<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Hanya izinkan pengguna dengan role 'admin'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('pengguna') || session('pengguna')['role'] !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
