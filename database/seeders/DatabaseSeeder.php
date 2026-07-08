<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Pengguna;
use App\Models\Pemasok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Pengguna ──────────────────────────────────────────────────
        $admin = Pengguna::create([
            'nama_pengguna' => 'Luthfy Arief',
            'username'      => 'admin',
            'password'      => Hash::make('admin123'),
            'role'          => 'admin',
        ]);

        Pengguna::create([
            'nama_pengguna' => 'Staff Bengkel',
            'username'      => 'staff',
            'password'      => Hash::make('password'),
            'role'          => 'staf',
        ]);

        Pengguna::create([
            'nama_pengguna' => 'Staff Operasional',
            'username'      => 'staf',
            'password'      => Hash::make('password'),
            'role'          => 'staf',
        ]);

        // ── Pemasok ───────────────────────────────────────────────────
        $p1 = Pemasok::create(['nama_pemasok' => 'PT Indopart Jaya',    'kontak' => '021-5551234',  'alamat' => 'Jl. Industri No. 12, Jakarta']);
        $p2 = Pemasok::create(['nama_pemasok' => 'UD Bengkel Sukses',   'kontak' => '022-7778899',  'alamat' => 'Jl. Otomotif No. 5, Bandung']);
        $p3 = Pemasok::create(['nama_pemasok' => 'CV Mitra Spare Part', 'kontak' => '0812-3456789', 'alamat' => 'Jl. Raya Cimahi No. 88, Cimahi']);

        // ── Barang ────────────────────────────────────────────────────
        $b1  = Barang::create(['kode_barang' => 'OLI-10W40-01', 'nama_barang' => 'Oli Mesin 10W-40',    'kategori' => 'Bahan Habis Pakai', 'stok' => 50, 'stok_minimum' => 10, 'harga_satuan' => 85000]);
        $b2  = Barang::create(['kode_barang' => 'FLT-OLI-01',   'nama_barang' => 'Filter Oli',           'kategori' => 'Suku Cadang',       'stok' => 30, 'stok_minimum' => 5,  'harga_satuan' => 45000]);
        $b3  = Barang::create(['kode_barang' => 'BSI-NGK-01',   'nama_barang' => 'Busi NGK',             'kategori' => 'Suku Cadang',       'stok' => 24, 'stok_minimum' => 8,  'harga_satuan' => 35000]);
        $b4  = Barang::create(['kode_barang' => 'KMP-REM-01',   'nama_barang' => 'Kampas Rem Depan',     'kategori' => 'Suku Cadang',       'stok' => 8,  'stok_minimum' => 4,  'harga_satuan' => 120000]);
        $b5  = Barang::create(['kode_barang' => 'KNC-TRSI-01',  'nama_barang' => 'Kunci Torsi 1/2"',     'kategori' => 'Alat Kerja',        'stok' => 3,  'stok_minimum' => 2,  'harga_satuan' => 350000]);
        $b6  = Barang::create(['kode_barang' => 'MYK-REM-04',   'nama_barang' => 'Minyak Rem DOT 4',     'kategori' => 'Bahan Habis Pakai', 'stok' => 15, 'stok_minimum' => 5,  'harga_satuan' => 65000]);
        $b7  = Barang::create(['kode_barang' => 'FAN-BLT-01',   'nama_barang' => 'Fan Belt',             'kategori' => 'Suku Cadang',       'stok' => 12, 'stok_minimum' => 3,  'harga_satuan' => 95000]);
        $b8  = Barang::create(['kode_barang' => 'LMP-LED-H4',   'nama_barang' => 'Lampu LED H4',         'kategori' => 'Suku Cadang',       'stok' => 20, 'stok_minimum' => 5,  'harga_satuan' => 75000]);
        $b9  = Barang::create(['kode_barang' => 'BRG-RDA-01',   'nama_barang' => 'Bearing Roda Depan',   'kategori' => 'Suku Cadang',       'stok' => 5,  'stok_minimum' => 5,  'harga_satuan' => 185000]);
        $b10 = Barang::create(['kode_barang' => 'RAD-CLT-01',   'nama_barang' => 'Radiator Coolant',     'kategori' => 'Bahan Habis Pakai', 'stok' => 18, 'stok_minimum' => 5,  'harga_satuan' => 55000]);

        // ── Barang Masuk ──────────────────────────────────────────────
        BarangMasuk::create(['id_barang' => $b1->id,  'id_pemasok' => $p1->id, 'tanggal_masuk' => '2026-07-01', 'jumlah_masuk' => 20]);
        BarangMasuk::create(['id_barang' => $b2->id,  'id_pemasok' => $p2->id, 'tanggal_masuk' => '2026-07-01', 'jumlah_masuk' => 15]);
        BarangMasuk::create(['id_barang' => $b3->id,  'id_pemasok' => $p3->id, 'tanggal_masuk' => '2026-07-02', 'jumlah_masuk' => 10]);
        BarangMasuk::create(['id_barang' => $b6->id,  'id_pemasok' => $p1->id, 'tanggal_masuk' => '2026-07-02', 'jumlah_masuk' => 5]);
        BarangMasuk::create(['id_barang' => $b10->id, 'id_pemasok' => $p2->id, 'tanggal_masuk' => '2026-07-03', 'jumlah_masuk' => 8]);

        // ── Barang Keluar ─────────────────────────────────────────────
        BarangKeluar::create(['id_barang' => $b1->id, 'tanggal_keluar' => '2026-07-02', 'jumlah_keluar' => 3, 'tujuan' => 'Perbaikan Motor Honda Beat']);
        BarangKeluar::create(['id_barang' => $b3->id, 'tanggal_keluar' => '2026-07-02', 'jumlah_keluar' => 2, 'tujuan' => 'Ganti Busi Toyota Avanza']);
        BarangKeluar::create(['id_barang' => $b4->id, 'tanggal_keluar' => '2026-07-03', 'jumlah_keluar' => 1, 'tujuan' => 'Perbaikan Rem Yamaha NMAX']);
        BarangKeluar::create(['id_barang' => $b7->id, 'tanggal_keluar' => '2026-07-03', 'jumlah_keluar' => 1, 'tujuan' => 'Ganti Fan Belt Suzuki Ertiga']);

        // ── Activity Logs Seeder ──────────────────────────────────────
        \App\Models\ActivityLog::create([
            'user_id' => $admin->id,
            'nama_user' => 'Luthfy Arief',
            'action' => 'SYSTEM',
            'description' => 'Inisialisasi sistem inventaris dan impor 10 data barang awal.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
