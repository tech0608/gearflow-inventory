<x-mail::message>
# ⚠️ Peringatan Stok Kritis

Halo, **Admin Inventaris Bengkel**!

Sistem mendeteksi bahwa barang berikut telah mencapai batas stok minimum dan memerlukan perhatian segera.

---

## Detail Barang

| Keterangan | Informasi |
|---|---|
| **Nama Barang** | {{ $barang->nama_barang }} |
| **SKU / Kode** | {{ $barang->kode_barang ?? '-' }} |
| **Kategori** | {{ $barang->kategori }} |
| **Stok Saat Ini** | **{{ $barang->stok }} unit** |
| **Stok Minimum** | {{ $barang->stok_minimum ?? 5 }} unit |
| **Status** | 🔴 KRITIS |
| **Harga Satuan** | Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }} |

---

> Dipicu oleh: **{{ $triggerUser }}** pada {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY – HH:mm') }} WIB

<x-mail::button :url="$appUrl . '/barang-masuk/tambah'" color="success">
📦 Tambah Stok Sekarang
</x-mail::button>

Segera lakukan pemesanan ulang ke pemasok untuk menghindari kehabisan stok.

Salam,
**{{ $appName }}** — Sistem Inventaris Barang Bengkel
</x-mail::message>
