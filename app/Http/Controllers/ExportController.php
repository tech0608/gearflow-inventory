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
    // HELPER: Bungkus data menjadi HTML Excel (xls format)
    // Excel membaca HTML table sebagai spreadsheet sempurna:
    // - Mendukung bold, warna, border, colspan, lebar kolom
    // - Tanggal tidak berubah jadi #######
    // - Tidak perlu library tambahan
    // ─────────────────────────────────────────────────────────────────────────
    private function excelHeader(string $filename): array
    {
        return [
            'Content-Type'        => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];
    }

    private function styleHead(): string
    {
        return '
        <style>
            body  { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
            table { border-collapse: collapse; width: 100%; }
            th    { background-color: #1e3a5f; color: #ffffff; font-weight: bold;
                    border: 1px solid #aaa; padding: 6px 10px; white-space: nowrap; }
            td    { border: 1px solid #ccc; padding: 5px 10px; vertical-align: top; }
            .title     { font-size: 16pt; font-weight: bold; color: #1e3a5f; }
            .subtitle  { font-size: 12pt; color: #444; }
            .instansi  { font-size: 10pt; color: #777; }
            .meta-label{ font-weight: bold; width: 160px; }
            .meta-val  { }
            .section   { background-color: #dbe9f4; font-weight: bold;
                         color: #1e3a5f; padding: 5px 10px;
                         border: 1px solid #aaa; }
            .row-masuk { background-color: #f0fff4; }
            .row-keluar{ background-color: #fff8f0; }
            .row-kritis{ background-color: #fff0f0; }
            .row-rendah{ background-color: #fffdf0; }
            .row-aman  { background-color: #f0fff4; }
            .badge-masuk  { color: #16a34a; font-weight: bold; }
            .badge-keluar { color: #ea580c; font-weight: bold; }
            .badge-kritis { color: #dc2626; font-weight: bold; }
            .badge-rendah { color: #ca8a04; font-weight: bold; }
            .badge-aman   { color: #16a34a; font-weight: bold; }
            .num   { text-align: right; }
            .center{ text-align: center; }
            .summary-table th { background-color: #374151; }
            .summary-table td { background-color: #f9fafb; font-weight: bold; }
            .footer { color: #999; font-size: 9pt; font-style: italic; }
        </style>';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 1. EXPORT DATA BARANG (Master Inventory)
    // ─────────────────────────────────────────────────────────────────────────
    public function exportBarang()
    {
        $barangs  = Barang::orderBy('kategori')->orderBy('nama_barang')->get();
        $filename = 'GearFlow_DataBarang_' . date('Ymd_Hi') . '.xls';

        $totalNilai  = $barangs->sum('nilai_stok');
        $totalUnit   = $barangs->sum('stok');
        $jmlAman     = $barangs->where('status_stok', 'aman')->count();
        $jmlRendah   = $barangs->where('status_stok', 'rendah')->count();
        $jmlKritis   = $barangs->where('status_stok', 'kritis')->count();

        $html  = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $html .= 'xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8">' . $this->styleHead() . '</head><body>';

        // ── HEADER LAPORAN ───────────────────────────────────────────────────
        $html .= '<table style="border:none;margin-bottom:10px">';
        $html .= '<tr><td style="border:none" class="title">GEARFLOW INVENTORY SYSTEM</td></tr>';
        $html .= '<tr><td style="border:none" class="subtitle">Laporan Master Data Inventaris Barang</td></tr>';
        $html .= '<tr><td style="border:none" class="instansi">Bengkel Otomotif &mdash; Universitas Teknologi Bandung</td></tr>';
        $html .= '<tr><td style="border:none">&nbsp;</td></tr>';
        $html .= '<tr>
            <td style="border:none" class="meta-label">Tanggal Cetak</td>
            <td style="border:none">: ' . date('d/m/Y H:i') . ' WIB</td>
        </tr>';
        $html .= '<tr>
            <td style="border:none" class="meta-label">Total Item Barang</td>
            <td style="border:none">: <b>' . $barangs->count() . ' item</b></td>
        </tr>';
        $html .= '<tr>
            <td style="border:none" class="meta-label">Total Nilai Inventaris</td>
            <td style="border:none">: <b>Rp ' . number_format($totalNilai, 0, ',', '.') . '</b></td>
        </tr>';
        $html .= '</table><br>';

        // ── TABEL DATA ───────────────────────────────────────────────────────
        $html .= '<table>';
        $html .= '<thead><tr>
            <th class="center" style="width:40px">No</th>
            <th style="width:120px">Kode SKU</th>
            <th style="width:220px">Nama Barang</th>
            <th style="width:120px">Kategori</th>
            <th class="num" style="width:80px">Stok</th>
            <th class="num" style="width:80px">Min. Stok</th>
            <th class="num" style="width:140px">Harga Satuan (Rp)</th>
            <th class="num" style="width:160px">Nilai Stok (Rp)</th>
            <th class="center" style="width:100px">Status</th>
        </tr></thead><tbody>';

        $kategoriSebelumnya = null;
        $no = 1;
        foreach ($barangs as $b) {
            // Baris pemisah antar kategori
            if ($b->kategori !== $kategoriSebelumnya) {
                $html .= '<tr><td colspan="9" class="section">&nbsp;&nbsp;&#9658; ' . strtoupper($b->kategori) . '</td></tr>';
                $kategoriSebelumnya = $b->kategori;
            }

            $statusClass = match(strtolower($b->status_stok)) {
                'kritis' => 'row-kritis',
                'rendah' => 'row-rendah',
                default  => 'row-aman',
            };
            $badgeClass = 'badge-' . strtolower($b->status_stok);
            $statusLabel = match(strtolower($b->status_stok)) {
                'kritis' => '&#9888; KRITIS',
                'rendah' => '&#8595; RENDAH',
                default  => '&#10003; AMAN',
            };

            $html .= "<tr class=\"{$statusClass}\">
                <td class=\"center\">{$no}</td>
                <td>" . htmlspecialchars($b->kode_barang ?? '-') . "</td>
                <td>" . htmlspecialchars($b->nama_barang) . "</td>
                <td>" . htmlspecialchars($b->kategori) . "</td>
                <td class=\"num\">" . number_format($b->stok, 0, ',', '.') . "</td>
                <td class=\"num\">" . number_format($b->stok_minimum ?: 5, 0, ',', '.') . "</td>
                <td class=\"num\">" . number_format($b->harga_satuan, 0, ',', '.') . "</td>
                <td class=\"num\">" . number_format($b->nilai_stok, 0, ',', '.') . "</td>
                <td class=\"center {$badgeClass}\">{$statusLabel}</td>
            </tr>";
            $no++;
        }
        $html .= '</tbody></table><br>';

        // ── RINGKASAN ────────────────────────────────────────────────────────
        $html .= '<table class="summary-table" style="width:480px">';
        $html .= '<thead><tr><th colspan="3">RINGKASAN INVENTARIS</th></tr></thead><tbody>';
        $html .= '<tr><td>Total Semua Barang</td><td class="num">' . $barangs->count() . ' item</td><td></td></tr>';
        $html .= '<tr><td>Total Unit Stok</td><td class="num">' . number_format($totalUnit, 0, ',', '.') . ' unit</td><td></td></tr>';
        $html .= '<tr><td>Total Nilai Inventaris</td><td class="num">Rp ' . number_format($totalNilai, 0, ',', '.') . '</td><td></td></tr>';
        $html .= '<tr><td class="badge-aman">&#10003; Status AMAN</td><td class="num badge-aman">' . $jmlAman . ' item</td><td></td></tr>';
        $html .= '<tr><td class="badge-rendah">&#8595; Status RENDAH</td><td class="num badge-rendah">' . $jmlRendah . ' item</td><td></td></tr>';
        $html .= '<tr><td class="badge-kritis">&#9888; Status KRITIS</td><td class="num badge-kritis">' . $jmlKritis . ' item</td><td></td></tr>';
        $html .= '</tbody></table><br>';
        $html .= '<p class="footer">Laporan ini digenerate secara otomatis oleh GearFlow Inventory System &mdash; ' . date('d/m/Y H:i') . ' WIB</p>';
        $html .= '</body></html>';

        ActivityLog::record('EXPORT', 'Mengunduh file Excel Data Barang');

        return response($html, 200, $this->excelHeader($filename));
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

        $transaksi   = $transaksi->sortBy('tanggal')->values();
        $totalMasuk  = $transaksi->where('tipe', 'MASUK')->sum('jumlah');
        $totalKeluar = $transaksi->where('tipe', 'KELUAR')->sum('jumlah');
        $selisih     = $totalMasuk - $totalKeluar;

        $judulTipe = match($tipe) {
            'masuk'  => 'Barang Masuk',
            'keluar' => 'Barang Keluar',
            default  => 'Semua Transaksi (Masuk & Keluar)',
        };

        $filename = 'GearFlow_LaporanTransaksi_' . date('Ymd_Hi') . '.xls';

        $html  = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $html .= 'xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8">' . $this->styleHead() . '</head><body>';

        // ── HEADER LAPORAN ───────────────────────────────────────────────────
        $html .= '<table style="border:none;margin-bottom:10px">';
        $html .= '<tr><td style="border:none" class="title">GEARFLOW INVENTORY SYSTEM</td></tr>';
        $html .= '<tr><td style="border:none" class="subtitle">Laporan Riwayat Transaksi &mdash; ' . $judulTipe . '</td></tr>';
        $html .= '<tr><td style="border:none" class="instansi">Bengkel Otomotif &mdash; Universitas Teknologi Bandung</td></tr>';
        $html .= '<tr><td style="border:none">&nbsp;</td></tr>';
        $html .= '<tr><td style="border:none" class="meta-label">Tanggal Cetak</td><td style="border:none">: ' . date('d/m/Y H:i') . ' WIB</td></tr>';
        $html .= '<tr><td style="border:none" class="meta-label">Periode Laporan</td><td style="border:none">: <b>' . date('d/m/Y', strtotime($dari)) . ' s/d ' . date('d/m/Y', strtotime($sampai)) . '</b></td></tr>';
        $html .= '<tr><td style="border:none" class="meta-label">Total Transaksi</td><td style="border:none">: <b>' . $transaksi->count() . ' transaksi</b></td></tr>';
        $html .= '<tr><td style="border:none" class="meta-label">Total Masuk</td><td style="border:none">: <b class="badge-masuk">+' . number_format($totalMasuk, 0, ',', '.') . ' unit</b></td></tr>';
        $html .= '<tr><td style="border:none" class="meta-label">Total Keluar</td><td style="border:none">: <b class="badge-keluar">-' . number_format($totalKeluar, 0, ',', '.') . ' unit</b></td></tr>';
        $html .= '</table><br>';

        // ── TABEL DATA ───────────────────────────────────────────────────────
        $html .= '<table>';
        $html .= '<thead><tr>
            <th class="center" style="width:40px">No</th>
            <th class="center" style="width:90px">Tanggal</th>
            <th class="center" style="width:90px">Jenis</th>
            <th style="width:110px">Kode SKU</th>
            <th style="width:220px">Nama Barang</th>
            <th style="width:110px">Kategori</th>
            <th style="width:220px">Keterangan</th>
            <th class="num" style="width:100px">Jumlah (Unit)</th>
        </tr></thead><tbody>';

        if ($transaksi->isEmpty()) {
            $html .= '<tr><td colspan="8" style="text-align:center;color:#999;padding:20px">
                Tidak ada data transaksi pada periode ini.
            </td></tr>';
        } else {
            $tanggalSebelumnya = null;
            foreach ($transaksi as $i => $t) {
                // Baris pemisah antar hari
                if ($t['tanggal'] !== $tanggalSebelumnya) {
                    $html .= '<tr><td colspan="8" class="section">&nbsp;&nbsp;&#9656; ' . $t['tanggal'] . '</td></tr>';
                    $tanggalSebelumnya = $t['tanggal'];
                }

                $rowClass   = $t['tipe'] === 'MASUK' ? 'row-masuk' : 'row-keluar';
                $badgeClass = $t['tipe'] === 'MASUK' ? 'badge-masuk' : 'badge-keluar';
                $badgeLabel = $t['tipe'] === 'MASUK' ? '&#8593; MASUK' : '&#8595; KELUAR';
                $jumlahStr  = ($t['tipe'] === 'MASUK' ? '+' : '-') . number_format($t['jumlah'], 0, ',', '.');

                $html .= "<tr class=\"{$rowClass}\">
                    <td class=\"center\">" . ($i + 1) . "</td>
                    <td class=\"center\">" . htmlspecialchars($t['tanggal']) . "</td>
                    <td class=\"center {$badgeClass}\">{$badgeLabel}</td>
                    <td>" . htmlspecialchars($t['sku']) . "</td>
                    <td>" . htmlspecialchars($t['barang']) . "</td>
                    <td>" . htmlspecialchars($t['kategori']) . "</td>
                    <td>" . htmlspecialchars($t['keterangan']) . "</td>
                    <td class=\"num {$badgeClass}\">{$jumlahStr}</td>
                </tr>";
            }
        }

        $html .= '</tbody></table><br>';

        // ── RINGKASAN ────────────────────────────────────────────────────────
        $html .= '<table class="summary-table" style="width:480px">';
        $html .= '<thead><tr><th colspan="2">RINGKASAN PERIODE</th></tr></thead><tbody>';
        $html .= '<tr><td>Transaksi Barang Masuk</td><td class="num badge-masuk">' . $transaksi->where('tipe','MASUK')->count() . ' transaksi &nbsp;|&nbsp; +' . number_format($totalMasuk, 0, ',', '.') . ' unit</td></tr>';
        $html .= '<tr><td>Transaksi Barang Keluar</td><td class="num badge-keluar">' . $transaksi->where('tipe','KELUAR')->count() . ' transaksi &nbsp;|&nbsp; -' . number_format($totalKeluar, 0, ',', '.') . ' unit</td></tr>';
        $html .= '<tr><td>Selisih Stok Bersih</td><td class="num ' . ($selisih >= 0 ? 'badge-aman' : 'badge-kritis') . '">' . ($selisih >= 0 ? '+' : '') . number_format($selisih, 0, ',', '.') . ' unit</td></tr>';
        $html .= '</tbody></table><br>';
        $html .= '<p class="footer">Laporan ini digenerate secara otomatis oleh GearFlow Inventory System &mdash; ' . date('d/m/Y H:i') . ' WIB</p>';
        $html .= '</body></html>';

        ActivityLog::record('EXPORT', "Mengunduh Excel Laporan Transaksi ({$dari} s/d {$sampai})");

        return response($html, 200, $this->excelHeader($filename));
    }
}
