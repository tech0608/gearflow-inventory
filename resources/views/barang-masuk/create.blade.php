@extends('layouts.app')
@section('title','Catat Barang Masuk')
@section('page-title','Catat Barang Masuk')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Catat Barang Masuk</h2>
    <p>Stok barang akan otomatis bertambah setelah disimpan</p>
  </div>
  <a href="{{ route('barang-masuk.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    <form method="POST" action="{{ route('barang-masuk.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Barang *</label>

        {{-- ── Barcode Scanner ── --}}
        <div style="display:flex;gap:8px;margin-bottom:8px">
          <button type="button" id="btn-scan-masuk"
            onclick="toggleScanner('scanner-masuk','btn-scan-masuk')"
            class="btn btn-ghost btn-sm"
            style="font-size:0.8rem;display:flex;align-items:center;gap:6px;border:1px solid var(--border);padding:6px 12px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M23 3H1v18h22V3z"/><path d="M7 7h.01M12 7h.01M17 7h.01M7 12h.01M12 12h.01M17 12h.01M7 17h.01M12 17h.01M17 17h.01"/>
            </svg>
            📷 Scan SKU Barcode
          </button>
          <span class="form-hint" style="margin:0;align-self:center;font-size:0.75rem">Scan barcode untuk otomatis pilih barang</span>
        </div>

        {{-- Area Scanner Kamera --}}
        <div id="scanner-masuk" style="display:none;margin-bottom:12px">
          <div id="reader-masuk" style="width:100%;border-radius:8px;overflow:hidden;border:2px solid var(--accent)"></div>
          <div id="scan-result-masuk" style="margin-top:8px;font-size:0.82rem;color:var(--success)"></div>
          <button type="button" onclick="stopScanner('reader-masuk','scanner-masuk','btn-scan-masuk')"
            class="btn btn-ghost btn-sm" style="margin-top:6px;font-size:0.78rem">✕ Tutup Scanner</button>
        </div>

        <select class="form-control" name="id_barang" required id="sel-barang" onchange="showStokInfo()">
          <option value="">-- Pilih Barang --</option>
          @foreach($barangs as $b)
            <option value="{{ $b->id }}"
              data-stok="{{ $b->stok }}"
              data-sku="{{ $b->kode_barang }}"
              {{ old('id_barang')==$b->id?'selected':'' }}>
              {{ $b->nama_barang }} &nbsp;(Stok: {{ $b->stok }})
            </option>
          @endforeach
        </select>
        <div class="form-hint" id="stok-info"></div>
        @error('id_barang')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>

      <div class="form-group">
        <label class="form-label">Pemasok *</label>
        <select class="form-control" name="id_pemasok" required>
          <option value="">-- Pilih Pemasok --</option>
          @foreach($pemasoks as $p)
            <option value="{{ $p->id }}" {{ old('id_pemasok')==$p->id?'selected':'' }}>{{ $p->nama_pemasok }}</option>
          @endforeach
        </select>
        @error('id_pemasok')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tanggal Masuk *</label>
          <input class="form-control" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
          @error('tanggal_masuk')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Jumlah Diterima *</label>
          <input class="form-control" type="number" name="jumlah_masuk" min="1" required
                 value="{{ old('jumlah_masuk') }}" placeholder="Jumlah unit">
          @error('jumlah_masuk')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-actions">
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-success">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Simpan Barang Masuk
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Html5-QRCode Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let activeScanner = null;

function toggleScanner(scannerDivId, btnId) {
  const scannerDiv = document.getElementById(scannerDivId);
  if (scannerDiv.style.display === 'none') {
    scannerDiv.style.display = 'block';
    document.getElementById(btnId).innerHTML = '⏹ Hentikan Scanner';
    startScanner(scannerDivId.replace('scanner-','reader-'), scannerDivId, btnId);
  } else {
    stopScanner(scannerDivId.replace('scanner-','reader-'), scannerDivId, btnId);
  }
}

function startScanner(readerId, scannerDivId, btnId) {
  if (activeScanner) {
    activeScanner.stop().catch(() => {});
  }
  activeScanner = new Html5Qrcode(readerId);
  activeScanner.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: { width: 250, height: 120 }, aspectRatio: 2.0 },
    (decodedText) => {
      handleScanResult(decodedText, scannerDivId, btnId);
    },
    () => {}
  ).catch(err => {
    document.getElementById(scannerDivId.replace('scanner-','scan-result-')).textContent =
      '⚠️ Kamera tidak dapat diakses: ' + err;
    document.getElementById(scannerDivId.replace('scanner-','scan-result-')).style.color = 'var(--danger)';
  });
}

function stopScanner(readerId, scannerDivId, btnId) {
  if (activeScanner) {
    activeScanner.stop().then(() => {
      activeScanner.clear();
      activeScanner = null;
    }).catch(() => {});
  }
  document.getElementById(scannerDivId).style.display = 'none';
  document.getElementById(btnId).innerHTML = `
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M23 3H1v18h22V3z"/></svg> 📷 Scan SKU Barcode`;
}

function handleScanResult(sku, scannerDivId, btnId) {
  stopScanner(scannerDivId.replace('scanner-','reader-'), scannerDivId, btnId);

  // Cari barang berdasarkan SKU di select
  const select = document.getElementById('sel-barang');
  let found = false;
  for (let i = 0; i < select.options.length; i++) {
    if (select.options[i].dataset.sku === sku) {
      select.selectedIndex = i;
      select.dispatchEvent(new Event('change'));
      found = true;
      break;
    }
  }

  const resultEl = document.getElementById('scan-result-masuk');
  if (found) {
    resultEl.textContent = '✅ Barang ditemukan: ' + sku;
    resultEl.style.color = 'var(--success)';
  } else {
    resultEl.textContent = '⚠️ SKU tidak ditemukan di sistem: ' + sku;
    resultEl.style.color = 'var(--warning)';
  }
  document.getElementById('scanner-masuk').style.display = 'block';
}

function showStokInfo() {
  const sel = document.getElementById('sel-barang');
  const opt = sel.options[sel.selectedIndex];
  const info = document.getElementById('stok-info');
  if (opt && opt.dataset.stok !== undefined && opt.value) {
    const stok = parseInt(opt.dataset.stok);
    info.textContent = 'Stok saat ini: ' + stok + ' unit';
    info.style.color = stok <= 5 ? 'var(--danger)' : stok <= 15 ? 'var(--warning)' : 'var(--success)';
  } else { info.textContent = ''; }
}
</script>
@endsection
