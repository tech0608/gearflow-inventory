<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportBarang()
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        $filename = "export_data_barang_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($barangs) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['No', 'Kode Barang (SKU)', 'Nama Barang', 'Kategori', 'Stok Saat Ini', 'Batas Stok Minimum', 'Harga Satuan (Rp)', 'Total Nilai Stok (Rp)', 'Status Stok'], ';');

            foreach ($barangs as $index => $b) {
                fputcsv($file, [
                    $index + 1,
                    $b->kode_barang ?? '-',
                    $b->nama_barang,
                    $b->kategori,
                    $b->stok,
                    $b->stok_minimum ?: 5,
                    number_format($b->harga_satuan, 0, ',', ''),
                    number_format($b->nilai_stok, 0, ',', ''),
                    strtoupper($b->status_stok)
                ], ';');
            }
            fclose($file);
        };

        ActivityLog::record('EXPORT', 'Mengunduh file CSV Data Barang');

        return response()->stream($callback, 200, $headers);
    }

    public function exportLaporan(Request $request)
    {
        $tipe   = $request->get('tipe', 'all');
        $dari   = $request->get('dari', date('Y-m-01'));
        $sampai = $request->get('sampai', date('Y-m-d'));

        $transaksi = collect();

        if ($tipe === 'all' || $tipe === 'masuk') {
            $masuks = BarangMasuk::with(['barang', 'pemasok'])
                        ->whereBetween('tanggal_masuk', [$dari, $sampai])
                        ->get()
                        ->map(fn($m) => [
                            'tipe'       => 'MASUK',
                            'tanggal'    => $m->tanggal_masuk->format('Y-m-d'),
                            'sku'        => $m->barang->kode_barang ?? '-',
                            'barang'     => $m->barang->nama_barang ?? '-',
                            'keterangan' => 'Dari: ' . ($m->pemasok->nama_pemasok ?? '-'),
                            'jumlah'     => $m->jumlah_masuk,
                        ]);
            $transaksi = $transaksi->concat($masuks);
        }

        if ($tipe === 'all' || $tipe === 'keluar') {
            $keluars = BarangKeluar::with('barang')
                        ->whereBetween('tanggal_keluar', [$dari, $sampai])
                        ->get()
                        ->map(fn($k) => [
                            'tipe'       => 'KELUAR',
                            'tanggal'    => $k->tanggal_keluar->format('Y-m-d'),
                            'sku'        => $k->barang->kode_barang ?? '-',
                            'barang'     => $k->barang->nama_barang ?? '-',
                            'keterangan' => 'Tujuan: ' . ($k->tujuan ?: '-'),
                            'jumlah'     => $k->jumlah_keluar,
                        ]);
            $transaksi = $transaksi->concat($keluars);
        }

        $transaksi = $transaksi->sortByDesc('tanggal')->values();
        $filename = "export_laporan_transaksi_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transaksi, $dari, $sampai) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['No', 'Jenis Transaksi', 'Tanggal', 'Kode SKU', 'Nama Barang', 'Keterangan', 'Jumlah Unit'], ';');

            foreach ($transaksi as $index => $t) {
                fputcsv($file, [
                    $index + 1,
                    $t['tipe'],
                    $t['tanggal'],
                    $t['sku'],
                    $t['barang'],
                    $t['keterangan'],
                    $t['jumlah']
                ], ';');
            }
            fclose($file);
        };

        ActivityLog::record('EXPORT', "Mengunduh CSV Laporan Transaksi ({$dari} s/d {$sampai})");

        return response()->stream($callback, 200, $headers);
    }
}
