<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $penggunas = Pengguna::when($q, fn($query) =>
                        $query->where('nama_pengguna', 'like', "%$q%")
                              ->orWhere('username', 'like', "%$q%"))
                    ->orderBy('role')
                    ->orderBy('nama_pengguna')
                    ->paginate(10)->withQueryString();

        return view('pengguna.index', compact('penggunas', 'q'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pengguna' => 'required|string|max:100',
            'username'      => 'required|string|max:50|unique:penggunas,username|alpha_num',
            'email'         => 'nullable|email|max:100',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:admin,staf',
        ], [
            'username.unique'    => 'Username sudah digunakan.',
            'username.alpha_num' => 'Username hanya boleh huruf dan angka.',
            'email.email'        => 'Format email tidak valid.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data['password'] = Hash::make($data['password']);
        $baru = Pengguna::create($data);

        \App\Models\ActivityLog::record('CREATE', 'Admin menambahkan pengguna baru: ' . $baru->nama_pengguna . ' (' . $baru->role . ')');

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(Pengguna $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $data = $request->validate([
            'nama_pengguna' => 'required|string|max:100',
            'username'      => 'required|string|max:50|alpha_num|unique:penggunas,username,' . $pengguna->id,
            'email'         => 'nullable|email|max:100',
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:admin,staf',
        ], [
            'username.unique'    => 'Username sudah digunakan.',
            'username.alpha_num' => 'Username hanya boleh huruf dan angka.',
            'email.email'        => 'Format email tidak valid.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cegah admin menghapus peran admin-nya sendiri jika dia satu-satunya admin
        $currentUserId = session('pengguna.id');
        if ($pengguna->id == $currentUserId && $data['role'] !== 'admin') {
            $adminCount = Pengguna::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak dapat mengubah peran – Anda adalah satu-satunya Admin dalam sistem.');
            }
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $pengguna->update($data);

        \App\Models\ActivityLog::record('UPDATE', 'Memperbarui data pengguna: ' . $pengguna->nama_pengguna);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        // Cegah hapus akun sendiri
        if ($pengguna->id == session('pengguna.id')) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cegah hapus satu-satunya admin
        if ($pengguna->role === 'admin' && Pengguna::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus – Admin ini adalah satu-satunya Admin dalam sistem.');
        }

        // Cegah hapus jika hanya tersisa 1 pengguna
        if (Pengguna::count() <= 1) {
            return back()->with('error', 'Minimal 1 pengguna harus ada dalam sistem.');
        }

        $nama = $pengguna->nama_pengguna;
        $pengguna->delete();

        \App\Models\ActivityLog::record('DELETE', 'Admin menghapus pengguna: ' . $nama);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna ' . $nama . ' berhasil dihapus.');
    }
}
