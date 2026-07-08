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
        $dari   = $request->get('dari', date('Y-m-01'));  // default: awal bulan ini
        $sampai = $request->get('sampai', date('Y-m-d')); // default: hari ini

        $masuk = collect();
        $keluar = collect();

        if ($tipe !== 'keluar') {
            $masuk = BarangMasuk::with(['barang','pemasok'])
                ->whereBetween('tanggal_masuk', [$dari, $sampai])
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
                ->whereBetween('tanggal_keluar', [$dari, $sampai])
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
