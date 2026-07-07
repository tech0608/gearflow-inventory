@extends('layouts.app')
@section('title','Data Barang')
@section('page-title','Data Barang')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Data Barang</h2>
    <p>Kelola semua jenis barang yang tersedia di bengkel</p>
  </div>
  <div style="display:flex; gap:8px; align-items:center">
    <button type="button" id="btn-scan-barang" onclick="toggleBarangScanner()" class="btn btn-primary no-print" style="background:linear-gradient(135deg,#3b82f6,#6366f1)">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M23 3H1v18h22V3z"/><path d="M7 7h.01M12 7h.01M17 7h.01M7 12h.01M12 12h.01M17 12h.01M7 17h.01M12 17h.01M17 17h.01"/>
      </svg>
      📷 Scan Barcode / QR Cari
    </button>
    <a href="{{ route('export.barang') }}" class="btn btn-ghost no-print">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Export Excel (.csv)
    </a>
  </div>
</div>

{{-- ── Scanner Barcode / QR Code untuk Pencarian ── --}}
<div id="scanner-container-barang" class="card no-print" style="display:none; margin-bottom:16px; border:2px solid var(--accent)">
  <div class="card-body" style="text-align:center; padding:16px">
    <h3 style="font-size:1rem; margin-bottom:8px">📷 Scan SKU Barcode / QR Code Barang</h3>
    <p class="text-muted text-sm" style="margin-bottom:12px">Arahkan kamera ke barcode/QR Code pada barang atau kemasan</p>
    <div id="reader-barang" style="max-width:400px; margin:0 auto; border-radius:8px; overflow:hidden"></div>
    <div id="scan-result-barang" style="margin-top:10px; font-weight:600; font-size:0.9rem"></div>
    <button type="button" onclick="stopBarangScanner()" class="btn btn-ghost btn-sm" style="margin-top:10px">✕ Tutup Kamera</button>
  </div>
</div>

<div class="card">
  @include('partials._table-header',['route'=>'barang.create','label'=>'Tambah Barang','q'=>$q,'placeholder'=>'Cari SKU, nama, atau kategori...'])
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>SKU</th><th>Nama Barang</th><th>Kategori</th>
        <th class="text-right">Stok</th><th class="text-right">Harga Satuan</th>
        <th class="text-right">Nilai Stok</th><th>Aksi</th>
      </tr></thead>
      <tbody>
      @forelse($barangs as $i => $b)
        <tr>
          <td class="text-muted text-sm">{{ $barangs->firstItem() + $i }}</td>
          <td><code style="background:rgba(255,255,255,0.05);padding:3px 6px;border-radius:4px;font-size:0.78rem;color:var(--accent)">{{ $b->kode_barang ?? '-' }}</code></td>
          <td><strong>{{ $b->nama_barang }}</strong></td>
          <td>
            @php $bc = ['Suku Cadang'=>'badge-blue','Alat Kerja'=>'badge-purple','Bahan Habis Pakai'=>'badge-orange']; @endphp
            <span class="badge {{ $bc[$b->kategori] ?? 'badge-gray' }}">{{ $b->kategori }}</span>
          </td>
          <td class="text-right num-cell {{ $b->status_stok === 'kritis' ? 'stok-low' : ($b->status_stok === 'rendah' ? 'stok-warn' : 'stok-ok') }}" title="Min. Stok: {{ $b->stok_minimum ?: 5 }}">
            {{ number_format($b->stok) }}
          </td>
          <td class="text-right num-cell">Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
          <td class="text-right num-cell">Rp {{ number_format($b->stok * $b->harga_satuan,0,',','.') }}</td>
          <td>
            <div class="actions-cell">
              <button type="button" class="btn btn-icon btn-ghost btn-sm btn-qr" title="Lihat QR Code" data-sku="{{ $b->kode_barang ?: 'BARANG-'.$b->id }}" data-nama="{{ $b->nama_barang }}" style="color:var(--info); border: 1px solid var(--border); padding: 5px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; cursor: pointer; background: var(--bg-input)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                  <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
              </button>
              <a href="{{ route('barang.edit',$b) }}" class="btn btn-icon btn-edit btn-sm" title="Edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              <form method="POST" action="{{ route('barang.destroy',$b) }}" onsubmit="return confirm('Hapus barang ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-icon btn-delete btn-sm" title="Hapus">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="7"><div class="empty-state"><p>Tidak ada data barang</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }} dari {{ $barangs->total() }} data</span>
    <div class="pagination-btns">{{ $barangs->links('partials._pagination') }}</div>
  </div>
</div>

{{-- Modal QR Code --}}
<div id="qrModal" class="qr-modal">
  <div class="qr-modal-content">
    <button class="qr-modal-close" onclick="closeQrModal()">&times;</button>
    <h3 style="margin-bottom:8px">QR Code SKU Barang</h3>
    <p id="qr-modal-nama" style="font-size:0.9rem; color:var(--text-secondary); margin-bottom:4px; font-weight:600;"></p>
    <p id="qr-modal-sku" style="font-size:0.8rem; font-family:monospace; color:var(--accent); font-weight:700; margin-bottom:12px;"></p>
    <div id="qr-code-container">
      <img id="qr-code-img" class="qr-code-img" src="" alt="QR Code" style="width: 160px; height: 160px;">
    </div>
    <div style="margin-top:10px">
      <button class="btn btn-ghost btn-sm" onclick="printQr()" style="font-size:0.78rem">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px">
          <polyline points="6 9 6 2 18 2 18 9"/>
          <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
          <rect x="6" y="14" width="12" height="8"/>
        </svg>
        Cetak QR Code
      </button>
    </div>
  </div>
</div>

<script>
  function closeQrModal() {
    document.getElementById('qrModal').classList.remove('show');
  }

  function printQr() {
    const sku = document.getElementById('qr-modal-sku').textContent;
    const nama = document.getElementById('qr-modal-nama').textContent;
    const imgUrl = document.getElementById('qr-code-img').src;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
      <html>
      <head>
        <title>Print QR Code - ${sku}</title>
        <style>
          body { font-family: 'Inter', sans-serif; text-align: center; padding: 40px; color: #333; }
          .container { border: 2px dashed #ccc; padding: 30px; display: inline-block; border-radius: 12px; }
          h2 { margin: 0 0 8px 0; font-size: 1.4rem; }
          p { margin: 0 0 20px 0; font-family: monospace; font-size: 1rem; color: #666; font-weight: bold; }
          img { border: 1px solid #eee; padding: 10px; border-radius: 8px; }
        </style>
      </head>
      <body onload="window.print(); window.close();">
        <div class="container">
          <h2>${nama}</h2>
          <p>${sku}</p>
          <img src="${imgUrl}" width="200" height="200" />
        </div>
      </body>
      </html>
    `);
    printWindow.document.close();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const qrButtons = document.querySelectorAll('.btn-qr');
    const modal = document.getElementById('qrModal');
    const qrImg = document.getElementById('qr-code-img');
    const qrNama = document.getElementById('qr-modal-nama');
    const qrSku = document.getElementById('qr-modal-sku');

    qrButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        const sku = this.getAttribute('data-sku') || 'NO-SKU';
        const nama = this.getAttribute('data-nama') || 'Barang';
        
        qrNama.textContent = nama;
        qrSku.textContent = sku;
        
        // Generate QR code using public API qrserver
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(sku)}`;
        
        modal.classList.add('show');
      });
    });

    // Close on click outside modal content
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeQrModal();
      }
    });
  });

  // ── Logika Scanner Barcode / QR Code untuk Pencarian ──
  let activeBarangScanner = null;

  function toggleBarangScanner() {
    const container = document.getElementById('scanner-container-barang');
    const btn = document.getElementById('btn-scan-barang');
    if (container.style.display === 'none') {
      container.style.display = 'block';
      btn.innerHTML = '⏹ Hentikan Scanner';
      startBarangScanner();
    } else {
      stopBarangScanner();
    }
  }

  function startBarangScanner() {
    if (activeBarangScanner) {
      activeBarangScanner.stop().catch(() => {});
    }
    activeBarangScanner = new Html5Qrcode('reader-barang');
    activeBarangScanner.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.5 },
      (decodedText) => {
        handleBarangScanResult(decodedText);
      },
      () => {}
    ).catch(err => {
      const res = document.getElementById('scan-result-barang');
      res.textContent = '⚠️ Kamera tidak dapat diakses: ' + err;
      res.style.color = 'var(--danger)';
    });
  }

  function stopBarangScanner() {
    if (activeBarangScanner) {
      activeBarangScanner.stop().then(() => {
        activeBarangScanner.clear();
        activeBarangScanner = null;
      }).catch(() => {});
    }
    document.getElementById('scanner-container-barang').style.display = 'none';
    const btn = document.getElementById('btn-scan-barang');
    if (btn) btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3H1v18h22V3z"/></svg> 📷 Scan Barcode / QR Cari';
  }

  function handleBarangScanResult(sku) {
    stopBarangScanner();
    const res = document.getElementById('scan-result-barang');
    res.textContent = '✅ Terdeteksi: ' + sku + '. Mencari...';
    res.style.color = 'var(--success)';
    
    // Otomatis redirect / submit pencarian dengan SKU yang dishare
    setTimeout(() => {
      window.location.href = `{{ route('barang.index') }}?q=${encodeURIComponent(sku)}`;
    }, 500);
  }
</script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endsection
