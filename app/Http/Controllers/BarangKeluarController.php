<?php

namespace App\Http\Controllers;

use App\Mail\StokKritisMail;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q', '');
        $keluars = BarangKeluar::with('barang')
                    ->when($q, function ($query) use ($q) {
                        $query->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', "%$q%"))
                              ->orWhere('tujuan', 'like', "%$q%");
                    })
                    ->orderByDesc('tanggal_keluar')->orderByDesc('id')
                    ->paginate(10)->withQueryString();

        return view('barang-keluar.index', compact('keluars', 'q'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('barang-keluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_barang'      => 'required|exists:barangs,id',
            'tanggal_keluar' => 'required|date',
            'jumlah_keluar'  => 'required|integer|min:1',
            'tujuan'         => 'nullable|string|max:200',
        ]);

        // Validasi stok cukup
        $barang = Barang::findOrFail($data['id_barang']);
        if ($data['jumlah_keluar'] > $barang->stok) {
            return back()
                ->withErrors(['jumlah_keluar' => "Stok tidak cukup! Stok tersedia: {$barang->stok} unit."])
                ->withInput();
        }

        BarangKeluar::create($data);

        // Kurangi stok (atomic)
        $barang->decrement('stok', $data['jumlah_keluar']);
        $barang->refresh(); // reload stok terbaru

        $triggerUser = session('pengguna.nama', 'Sistem');
        \App\Models\ActivityLog::record(
            'KELUAR',
            'Mencatat keluar ' . number_format($data['jumlah_keluar']) . ' unit dari '
            . $barang->nama_barang . ' (' . ($data['tujuan'] ?: 'Tanpa keterangan') . '). Sisa stok: ' . $barang->stok
        );

        // ── Kirim Email Notifikasi Stok Kritis ──────────────────────────────
        $minStok = $barang->stok_minimum ?? 5;
        if ($barang->stok <= $minStok) {
            $this->kirimNotifikasiStokKritis($barang, $triggerUser);
        }

        $successMsg = 'Barang keluar berhasil dicatat. Stok otomatis berkurang.';
        if ($barang->stok <= $minStok) {
            $successMsg .= ' ⚠️ Stok ' . $barang->nama_barang . ' sudah kritis (' . $barang->stok . ' unit)! Email notifikasi telah dikirim ke Admin.';
        }

        return redirect()->route('barang-keluar.index')->with('success', $successMsg);
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        // Kembalikan stok
        $barang = $barangKeluar->barang;
        $nama   = $barang ? $barang->nama_barang : 'Barang Terhapus';
        $qty    = $barangKeluar->jumlah_keluar;

        if ($barang) {
            $barang->increment('stok', $qty);
        }

        $barangKeluar->delete();

        \App\Models\ActivityLog::record('DELETE', 'Menghapus transaksi keluar ' . number_format($qty) . ' unit (' . $nama . '). Stok dikembalikan.');

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi keluar dihapus. Stok dikembalikan.');
    }

    /**
     * Kirim email notifikasi stok kritis ke semua Admin yang punya email.
     */
    private function kirimNotifikasiStokKritis(Barang $barang, string $triggerUser): void
    {
        try {
            // Kirim ke semua pengguna Admin yang memiliki email
            $admins = Pengguna::where('role', 'admin')
                               ->whereNotNull('email')
                               ->where('email', '!=', '')
                               ->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new StokKritisMail($barang, $triggerUser));
            }

            // Jika tidak ada admin dengan email, log saja
            if ($admins->isEmpty()) {
                \App\Models\ActivityLog::record(
                    'SYSTEM',
                    '⚠️ Stok kritis terdeteksi: ' . $barang->nama_barang . ' (' . $barang->stok . ' unit). '
                    . 'Tidak ada Admin dengan email – notifikasi tidak terkirim.'
                );
            } else {
                \App\Models\ActivityLog::record(
                    'SYSTEM',
                    '📧 Email notifikasi stok kritis dikirim untuk: ' . $barang->nama_barang
                    . ' (' . $barang->stok . ' unit) ke ' . $admins->count() . ' Admin.'
                );
            }
        } catch (\Throwable $e) {
            // Jangan hentikan eksekusi utama jika email gagal
            \App\Models\ActivityLog::record('SYSTEM', '⚠️ Gagal kirim email notifikasi: ' . $e->getMessage());
        }
    }
}
