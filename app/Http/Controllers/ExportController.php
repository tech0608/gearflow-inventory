<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: tulis 1 baris CSV ke file handle
    // ─────────────────────────────────────────────────────────────────────────
    private function row($file, array $cols): void
    {
        fputcsv($file, $cols, ';');
    }

    private function blank($file, int $n = 1): void
    {
        for ($i = 0; $i < $n; $i++) {
            fputcsv($file, [''], ';');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. EXPORT DATA BARANG (Master Inventory)
    // ─────────────────────────────────────────────────────────────────────────
    public function exportBarang()
    {
        $barangs   = Barang::orderBy('kategori')->orderBy('nama_barang')->get();
        $filename  = 'GearFlow_DataBarang_' . date('Ymd_Hi') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($barangs) {
            $f = fopen('php://output', 'w');
            // BOM agar Excel bisa baca UTF-8 dengan benar
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ── BLOK JUDUL ────────────────────────────────────────────────
            $this->row($f, ['GEARFLOW INVENTORY SYSTEM']);
            $this->row($f, ['Laporan Master Data Inventaris Barang']);
            $this->row($f, ['Bengkel Otomotif – Universitas Teknologi Bandung']);
            $this->blank($f);
            $this->row($f, ['Tanggal Cetak', date('d/m/Y H:i')]);
            $this->row($f, ['Total Barang',  $barangs->count() . ' item']);
            $this->row($f, ['Total Nilai Inventaris', 'Rp ' . number_format($barangs->sum('nilai_stok'), 0, ',', '.')]);
            $this->blank($f, 2);

            // ── HEADER KOLOM ──────────────────────────────────────────────
            $this->row($f, [
                'No',
                'Kode SKU',
                'Nama Barang',
                'Kategori',
                'Stok Saat Ini',
                'Batas Minimum',
                'Harga Satuan (Rp)',
                'Nilai Stok (Rp)',
                'Status',
            ]);

            // ── DATA BARIS ────────────────────────────────────────────────
            $kategoriSebelumnya = null;
            $no = 1;
            foreach ($barangs as $b) {
                // Pemisah antar kategori
                if ($b->kategori !== $kategoriSebelumnya) {
                    if ($kategoriSebelumnya !== null) {
                        $this->blank($f);
                    }
                    $this->row($f, ['── ' . strtoupper($b->kategori) . ' ──']);
                    $kategoriSebelumnya = $b->kategori;
                }

                $status = match(strtolower($b->status_stok)) {
                    'kritis' => '⚠ KRITIS',
                    'rendah' => '↓ RENDAH',
                    default  => '✓ AMAN',
                };

                $this->row($f, [
                    $no++,
                    $b->kode_barang ?? '-',
                    $b->nama_barang,
                    $b->kategori,
                    $b->stok,
                    $b->stok_minimum ?: 5,
                    number_format($b->harga_satuan, 0, ',', '.'),
                    number_format($b->nilai_stok,   0, ',', '.'),
                    $status,
                ]);
            }

            // ── BARIS SUMMARY ─────────────────────────────────────────────
            $this->blank($f, 2);
            $this->row($f, ['RINGKASAN']);
            $this->row($f, ['Total Semua Barang',   '', $barangs->count() . ' item']);
            $this->row($f, ['Total Unit Stok',       '', number_format($barangs->sum('stok'), 0, ',', '.') . ' unit']);
            $this->row($f, ['Total Nilai Inventaris','', 'Rp ' . number_format($barangs->sum('nilai_stok'), 0, ',', '.')]);
            $this->row($f, ['Barang Status AMAN',    '', $barangs->where('status_stok', 'aman')->count()   . ' item']);
            $this->row($f, ['Barang Status RENDAH',  '', $barangs->where('status_stok', 'rendah')->count() . ' item']);
            $this->row($f, ['Barang Status KRITIS',  '', $barangs->where('status_stok', 'kritis')->count() . ' item']);

            fclose($f);
        };

        ActivityLog::record('EXPORT', 'Mengunduh file CSV Master Data Barang');

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. EXPORT LAPORAN TRANSAKSI (Barang Masuk & Keluar)
    // ─────────────────────────────────────────────────────────────────────────
    public function exportLaporan(Request $request)
    {
        $tipe   = $request->get('tipe', 'all');
        $dari   = $request->get('dari',   date('Y-m-01'));
        $sampai = $request->get('sampai', date('Y-m-d'));

        $transaksi = collect();

        if ($tipe === 'all' || $tipe === 'masuk') {
            $masuks = BarangMasuk::with(['barang', 'pemasok'])
                        ->whereBetween('tanggal_masuk', [$dari, $sampai])
                        ->orderBy('tanggal_masuk')
                        ->get()
                        ->map(fn($m) => [
                            'tipe'       => 'MASUK',
                            'tanggal'    => $m->tanggal_masuk->format('d/m/Y'),
                            'sku'        => $m->barang->kode_barang ?? '-',
                            'barang'     => $m->barang->nama_barang ?? '-',
                            'kategori'   => $m->barang->kategori    ?? '-',
                            'keterangan' => 'Supplier: ' . ($m->pemasok->nama_pemasok ?? '-'),
                            'jumlah'     => $m->jumlah_masuk,
                        ]);
            $transaksi = $transaksi->concat($masuks);
        }

        if ($tipe === 'all' || $tipe === 'keluar') {
            $keluars = BarangKeluar::with('barang')
                        ->whereBetween('tanggal_keluar', [$dari, $sampai])
                        ->orderBy('tanggal_keluar')
                        ->get()
                        ->map(fn($k) => [
                            'tipe'       => 'KELUAR',
                            'tanggal'    => $k->tanggal_keluar->format('d/m/Y'),
                            'sku'        => $k->barang->kode_barang ?? '-',
                            'barang'     => $k->barang->nama_barang ?? '-',
                            'kategori'   => $k->barang->kategori    ?? '-',
                            'keterangan' => $k->tujuan ?: '-',
                            'jumlah'     => $k->jumlah_keluar,
                        ]);
            $transaksi = $transaksi->concat($keluars);
        }

        $transaksi = $transaksi->sortBy('tanggal')->values();

        $totalMasuk  = $transaksi->where('tipe', 'MASUK')->sum('jumlah');
        $totalKeluar = $transaksi->where('tipe', 'KELUAR')->sum('jumlah');
        $jumlahTipe  = match($tipe) {
            'masuk'  => 'Barang Masuk',
            'keluar' => 'Barang Keluar',
            default  => 'Semua Transaksi',
        };

        $filename = 'GearFlow_Laporan_Transaksi_' . date('Ymd_Hi') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($transaksi, $dari, $sampai, $jumlahTipe, $totalMasuk, $totalKeluar) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ── BLOK JUDUL ────────────────────────────────────────────────
            $this->row($f, ['GEARFLOW INVENTORY SYSTEM']);
            $this->row($f, ['Laporan Riwayat Transaksi – ' . $jumlahTipe]);
            $this->row($f, ['Bengkel Otomotif – Universitas Teknologi Bandung']);
            $this->blank($f);
            $this->row($f, ['Tanggal Cetak',      date('d/m/Y H:i')]);
            $this->row($f, ['Periode Laporan',    date('d/m/Y', strtotime($dari)) . ' s/d ' . date('d/m/Y', strtotime($sampai))]);
            $this->row($f, ['Total Transaksi',    $transaksi->count() . ' transaksi']);
            $this->row($f, ['Total Masuk',        number_format($totalMasuk, 0, ',', '.') . ' unit']);
            $this->row($f, ['Total Keluar',       number_format($totalKeluar, 0, ',', '.') . ' unit']);
            $this->blank($f, 2);

            // ── HEADER KOLOM ──────────────────────────────────────────────
            $this->row($f, [
                'No',
                'Tanggal',
                'Jenis',
                'Kode SKU',
                'Nama Barang',
                'Kategori',
                'Keterangan',
                'Jumlah (Unit)',
            ]);

            // ── DATA BARIS ────────────────────────────────────────────────
            if ($transaksi->isEmpty()) {
                $this->row($f, ['', '', '', '', 'Tidak ada data pada periode ini.']);
            } else {
                $tanggalSebelumnya = null;
                foreach ($transaksi as $i => $t) {
                    // Pemisah antar hari
                    if ($t['tanggal'] !== $tanggalSebelumnya) {
                        if ($tanggalSebelumnya !== null) {
                            $this->blank($f);
                        }
                        $tanggalSebelumnya = $t['tanggal'];
                    }

                    $this->row($f, [
                        $i + 1,
                        $t['tanggal'],
                        $t['tipe'],
                        $t['sku'],
                        $t['barang'],
                        $t['kategori'],
                        $t['keterangan'],
                        ($t['tipe'] === 'MASUK' ? '+' : '-') . $t['jumlah'],
                    ]);
                }
            }

            // ── BARIS SUMMARY ─────────────────────────────────────────────
            $this->blank($f, 2);
            $this->row($f, ['RINGKASAN PERIODE']);
            $this->row($f, ['Total Transaksi Masuk',  '', $transaksi->where('tipe','MASUK')->count()  . ' transaksi', '', '+' . number_format($totalMasuk,  0, ',', '.') . ' unit']);
            $this->row($f, ['Total Transaksi Keluar', '', $transaksi->where('tipe','KELUAR')->count() . ' transaksi', '', '-' . number_format($totalKeluar, 0, ',', '.') . ' unit']);
            $this->row($f, ['Selisih Stok Bersih',    '', '',                                                          '', ($totalMasuk - $totalKeluar >= 0 ? '+' : '') . number_format($totalMasuk - $totalKeluar, 0, ',', '.') . ' unit']);
            $this->blank($f);
            $this->row($f, ['-- Laporan ini digenerate secara otomatis oleh GearFlow Inventory System --']);

            fclose($f);
        };

        ActivityLog::record('EXPORT', "Mengunduh CSV Laporan Transaksi ({$dari} s/d {$sampai})");

        return response()->stream($callback, 200, $headers);
    }
}
