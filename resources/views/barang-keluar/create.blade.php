@extends('layouts.app')
@section('title','Catat Barang Keluar')
@section('page-title','Catat Barang Keluar')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Catat Barang Keluar</h2>
    <p>Stok barang akan otomatis berkurang setelah disimpan</p>
  </div>
  <a href="{{ route('barang-keluar.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    @if($errors->any())
      <div class="alert alert-danger" style="margin-bottom:16px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $errors->first() }}
      </div>
    @endif
    <form method="POST" action="{{ route('barang-keluar.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Barang *</label>

        {{-- ── Barcode Scanner ── --}}
        <div style="display:flex;gap:8px;margin-bottom:8px">
          <button type="button" id="btn-scan-keluar"
            onclick="toggleScanner('scanner-keluar','btn-scan-keluar')"
            class="btn btn-ghost btn-sm"
            style="font-size:0.8rem;display:flex;align-items:center;gap:6px;border:1px solid var(--border);padding:6px 12px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M23 3H1v18h22V3z"/>
            </svg>
            📷 Scan SKU Barcode
          </button>
          <span class="form-hint" style="margin:0;align-self:center;font-size:0.75rem">Scan barcode untuk otomatis pilih barang</span>
        </div>

        {{-- Area Scanner Kamera --}}
        <div id="scanner-keluar" style="display:none;margin-bottom:12px">
          <div id="reader-keluar" style="width:100%;border-radius:8px;overflow:hidden;border:2px solid var(--warning)"></div>
          <div id="scan-result-keluar" style="margin-top:8px;font-size:0.82rem;color:var(--success)"></div>
          <button type="button" onclick="stopScanner('reader-keluar','scanner-keluar','btn-scan-keluar')"
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

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tanggal Keluar *</label>
          <input class="form-control" type="date" name="tanggal_keluar" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
          @error('tanggal_keluar')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Jumlah Keluar *</label>
          <input class="form-control" type="number" name="jumlah_keluar" id="inp-jumlah" min="1" required
                 value="{{ old('jumlah_keluar') }}" placeholder="Jumlah unit">
          @error('jumlah_keluar')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Tujuan Penggunaan</label>
        <input class="form-control" name="tujuan" value="{{ old('tujuan') }}"
               placeholder="Contoh: Perbaikan Motor Honda Beat">
        @error('tujuan')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-actions">
        <a href="{{ route('barang-keluar.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,var(--warning),#d97706)">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Simpan Barang Keluar
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
    const resultId = scannerDivId.replace('scanner-', 'scan-result-');
    document.getElementById(resultId).textContent = '⚠️ Kamera tidak dapat diakses: ' + err;
    document.getElementById(resultId).style.color = 'var(--danger)';
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
  const btn = document.getElementById(btnId);
  if (btn) btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3H1v18h22V3z"/></svg> 📷 Scan SKU Barcode`;
}

function handleScanResult(sku, scannerDivId, btnId) {
  stopScanner(scannerDivId.replace('scanner-','reader-'), scannerDivId, btnId);

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

  const resultEl = document.getElementById('scan-result-keluar');
  document.getElementById('scanner-keluar').style.display = 'block';
  if (found) {
    resultEl.textContent = '✅ Barang ditemukan: ' + sku;
    resultEl.style.color = 'var(--success)';
  } else {
    resultEl.textContent = '⚠️ SKU tidak ditemukan: ' + sku;
    resultEl.style.color = 'var(--warning)';
  }
}

function showStokInfo() {
  const sel  = document.getElementById('sel-barang');
  const opt  = sel.options[sel.selectedIndex];
  const info = document.getElementById('stok-info');
  const inp  = document.getElementById('inp-jumlah');
  if (opt && opt.dataset.stok !== undefined && opt.value) {
    const stok = parseInt(opt.dataset.stok);
    info.textContent = 'Stok tersedia: ' + stok + ' unit';
    info.style.color = stok <= 5 ? 'var(--danger)' : stok <= 15 ? 'var(--warning)' : 'var(--success)';
    if (inp) inp.max = stok;
  } else { info.textContent = ''; }
}
</script>
@endsection
