<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('pengguna')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // Throttle: max 5 percobaan per menit per IP
        $key      = 'login_attempts_' . $request->ip();
        $attempts = cache()->get($key, 0);
        if ($attempts >= 5) {
            return back()->withErrors(['username' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.']);
        }

        $pengguna = Pengguna::where('username', $request->username)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            cache()->put($key, $attempts + 1, 60);
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }

        // Reset attempts on success
        cache()->forget($key);

        // Simpan ke session
        session([
            'pengguna' => [
                'id'       => $pengguna->id,
                'nama'     => $pengguna->nama_pengguna,
                'username' => $pengguna->username,
                'role'     => $pengguna->role,
            ]
        ]);

        \App\Models\ActivityLog::record('LOGIN', 'Pengguna ' . $pengguna->nama_pengguna . ' berhasil login.');

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $pengguna->nama_pengguna . '!');
    }

    public function logout(Request $request)
    {
        if (session('pengguna')) {
            \App\Models\ActivityLog::record('LOGOUT', 'Pengguna keluar dari sistem.');
        }

        $request->session()->forget('pengguna');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Registrasi publik DINONAKTIFKAN.
    // Penambahan pengguna hanya dilakukan oleh Admin melalui menu "Pengguna".
    // Method di bawah tetap dipertahankan agar tidak ada broken reference,
    // namun route-nya sudah dihapus dari web.php.
    // ──────────────────────────────────────────────────────────────────────
    public function showRegister()
    {
        // Redirect ke login – registrasi publik tidak diizinkan
        return redirect()->route('login')
            ->with('error', 'Registrasi publik tidak tersedia. Hubungi Admin untuk mendapatkan akun.');
    }

    public function register(Request $request)
    {
        // Registrasi publik tidak diizinkan
        abort(403, 'Registrasi publik dinonaktifkan. Hubungi Admin.');
    }
}
