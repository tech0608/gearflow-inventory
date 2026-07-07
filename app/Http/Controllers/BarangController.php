<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $barangs = Barang::when($q, function ($query) use ($q) {
                        $query->where('nama_barang', 'like', "%$q%")
                              ->orWhere('kode_barang', 'like', "%$q%")
                              ->orWhere('kategori', 'like', "%$q%");
                    })
                    ->orderBy('nama_barang')
                    ->paginate(10)
                    ->withQueryString();

        return view('barang.index', compact('barangs', 'q'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_barang'  => 'nullable|string|max:30|unique:barangs,kode_barang',
            'nama_barang'  => 'required|string|max:100',
            'kategori'     => 'required|in:Suku Cadang,Alat Kerja,Bahan Habis Pakai',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        if (empty($data['stok_minimum'])) $data['stok_minimum'] = 5;

        $barang = Barang::create($data);

        \App\Models\ActivityLog::record('CREATE', 'Menambahkan barang baru: ' . $barang->nama_barang . ' (' . ($barang->kode_barang ?: '-') . ')');

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'kode_barang'  => 'nullable|string|max:30|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang'  => 'required|string|max:100',
            'kategori'     => 'required|in:Suku Cadang,Alat Kerja,Bahan Habis Pakai',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        if (empty($data['stok_minimum'])) $data['stok_minimum'] = 5;

        $barang->update($data);

        \App\Models\ActivityLog::record('UPDATE', 'Memperbarui data barang: ' . $barang->nama_barang);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $nama = $barang->nama_barang;
        $barang->delete();

        \App\Models\ActivityLog::record('DELETE', 'Menghapus barang: ' . $nama);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
