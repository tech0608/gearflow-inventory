<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q','');
        $pemasoks = Pemasok::when($q, fn($query) =>
                        $query->where('nama_pemasok','like',"%$q%")
                              ->orWhere('kontak','like',"%$q%")
                              ->orWhere('alamat','like',"%$q%"))
                    ->orderBy('nama_pemasok')
                    ->paginate(10)->withQueryString();

        return view('pemasok.index', compact('pemasoks','q'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pemasok' => 'required|string|max:100',
            'kontak'       => 'nullable|string|max:50',
            'alamat'       => 'nullable|string|max:200',
        ]);

        $pemasok = Pemasok::create($data);
        \App\Models\ActivityLog::record('CREATE', 'Menambahkan mitra pemasok baru: ' . $pemasok->nama_pemasok);
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $data = $request->validate([
            'nama_pemasok' => 'required|string|max:100',
            'kontak'       => 'nullable|string|max:50',
            'alamat'       => 'nullable|string|max:200',
        ]);

        $pemasok->update($data);
        \App\Models\ActivityLog::record('UPDATE', 'Memperbarui data mitra pemasok: ' . $pemasok->nama_pemasok);
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        $nama = $pemasok->nama_pemasok;
        $pemasok->delete();
        \App\Models\ActivityLog::record('DELETE', 'Menghapus mitra pemasok: ' . $nama);
        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus.');
    }
}
