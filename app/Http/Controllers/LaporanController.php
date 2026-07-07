<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tipe   = $request->get('tipe', 'all');
        $dari   = $request->get('dari', '');
        $sampai = $request->get('sampai', '');

        $masuk = collect();
        $keluar = collect();

        if ($tipe !== 'keluar') {
            $masuk = BarangMasuk::with(['barang','pemasok'])
                ->when($dari,   fn($q) => $q->where('tanggal_masuk', '>=', $dari))
                ->when($sampai, fn($q) => $q->where('tanggal_masuk', '<=', $sampai))
                ->orderByDesc('tanggal_masuk')->get()
                ->map(fn($m) => [
                    'tipe'        => 'masuk',
                    'tanggal'     => $m->tanggal_masuk,
                    'barang'      => $m->barang->nama_barang ?? '-',
                    'keterangan'  => $m->pemasok->nama_pemasok ?? '-',
                    'jumlah'      => $m->jumlah_masuk,
                ]);
        }

        if ($tipe !== 'masuk') {
            $keluar = BarangKeluar::with('barang')
                ->when($dari,   fn($q) => $q->where('tanggal_keluar', '>=', $dari))
                ->when($sampai, fn($q) => $q->where('tanggal_keluar', '<=', $sampai))
                ->orderByDesc('tanggal_keluar')->get()
                ->map(fn($k) => [
                    'tipe'       => 'keluar',
                    'tanggal'    => $k->tanggal_keluar,
                    'barang'     => $k->barang->nama_barang ?? '-',
                    'keterangan' => $k->tujuan ?? '-',
                    'jumlah'     => $k->jumlah_keluar,
                ]);
        }

        $transaksi = $masuk->concat($keluar)
                        ->sortByDesc('tanggal')
                        ->values();

        $totalMasuk  = $masuk->sum('jumlah');
        $totalKeluar = $keluar->sum('jumlah');

        return view('laporan.index', compact('transaksi','tipe','dari','sampai','totalMasuk','totalKeluar'));
    }
}
