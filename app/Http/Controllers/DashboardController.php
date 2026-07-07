<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Pemasok;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $allBarangs = Barang::all();

        $stokKritis = $allBarangs->filter(fn($b) => $b->stok <= ($b->stok_minimum ?: 5))->count();
        $stokRendah = $allBarangs->filter(fn($b) => $b->stok <= ($b->stok_minimum ?: 5) * 2)->count();

        $stats = [
            'total_barang'        => $allBarangs->count(),
            'total_pemasok'       => Pemasok::count(),
            'total_pengguna'      => Pengguna::count(),
            'masuk_hari_ini'      => BarangMasuk::where('tanggal_masuk', $today)->count(),
            'keluar_hari_ini'     => BarangKeluar::where('tanggal_keluar', $today)->count(),
            'stok_kritis'         => $stokKritis,
            'stok_rendah'         => $stokRendah,
            'nilai_inventaris'    => $allBarangs->sum(fn($b) => $b->stok * $b->harga_satuan),
        ];

        // ── Data Grafik Tren 7 Hari Terakhir ──────────────────────────
        $chartDates  = [];
        $chartMasuk  = [];
        $chartKeluar = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $chartDates[]  = now()->subDays($i)->format('d M');
            $chartMasuk[]  = BarangMasuk::where('tanggal_masuk', $d)->sum('jumlah_masuk');
            $chartKeluar[] = BarangKeluar::where('tanggal_keluar', $d)->sum('jumlah_keluar');
        }

        // ── Data Komposisi Kategori ───────────────────────────────────
        $kategoriLabels = ['Suku Cadang', 'Alat Kerja', 'Bahan Habis Pakai'];
        $kategoriValues = [];
        foreach ($kategoriLabels as $kat) {
            $kategoriValues[] = $allBarangs->where('kategori', $kat)->sum('stok');
        }

        $recentMasuk  = BarangMasuk::with(['barang','pemasok'])
                            ->orderByDesc('tanggal_masuk')->orderByDesc('id')
                            ->take(5)->get();

        $recentKeluar = BarangKeluar::with('barang')
                            ->orderByDesc('tanggal_keluar')->orderByDesc('id')
                            ->take(5)->get();

        $stokMonitor  = $allBarangs->sortBy('stok')->take(8);
        $maxStok = $allBarangs->max('stok') ?: 1;

        // Daftar barang dengan stok kritis untuk alert banner
        $kritisItems = $allBarangs->filter(fn($b) => $b->stok <= ($b->stok_minimum ?: 5))->values();

        return view('dashboard', compact(
            'stats','recentMasuk','recentKeluar','stokMonitor','maxStok',
            'chartDates','chartMasuk','chartKeluar','kategoriLabels','kategoriValues','kritisItems'
        ));
    }
}
