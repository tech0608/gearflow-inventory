<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $masuks = BarangMasuk::with(['barang','pemasok'])
                    ->when($q, function ($query) use ($q) {
                        $query->whereHas('barang', fn($b) => $b->where('nama_barang','like',"%$q%"))
                              ->orWhereHas('pemasok', fn($p) => $p->where('nama_pemasok','like',"%$q%"));
                    })
                    ->orderByDesc('tanggal_masuk')->orderByDesc('id')
                    ->paginate(10)->withQueryString();

        return view('barang-masuk.index', compact('masuks','q'));
    }

    public function create()
    {
        $barangs  = Barang::orderBy('nama_barang')->get();
        $pemasoks = Pemasok::orderBy('nama_pemasok')->get();
        return view('barang-masuk.create', compact('barangs','pemasoks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang'    => 'required|exists:barangs,id',
            'id_pemasok'   => 'required|exists:pemasoks,id',
            'tanggal_masuk'=> 'required|date',
            'jumlah_masuk' => 'required|integer|min:1',
        ]);

        BarangMasuk::create($data);

        // Tambah stok barang (atomic update)
        $barang = Barang::find($data['id_barang']);
        $barang->increment('stok', $data['jumlah_masuk']);

        \App\Models\ActivityLog::record('MASUK', 'Mencatat masuk ' . number_format($data['jumlah_masuk']) . ' unit untuk ' . $barang->nama_barang);

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dicatat. Stok otomatis bertambah.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        // Kurangi stok kembali (pastikan tidak negatif)
        $barang = $barangMasuk->barang;
        $nama = $barang ? $barang->nama_barang : 'Barang Terhapus';
        $qty = $barangMasuk->jumlah_masuk;

        if ($barang) {
            $barang->decrement('stok', min($qty, $barang->stok));
        }

        $barangMasuk->delete();

        \App\Models\ActivityLog::record('DELETE', 'Menghapus transaksi masuk ' . number_format($qty) . ' unit (' . $nama . ')');

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi masuk dihapus.');
    }
}
