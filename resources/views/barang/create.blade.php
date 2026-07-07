@extends('layouts.app')
@section('title','Tambah Barang')
@section('page-title','Tambah Barang')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Tambah Barang</h2>
    <p>Daftarkan barang baru ke dalam sistem</p>
  </div>
  <a href="{{ route('barang.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:580px">
  <div class="card-body">
    <form method="POST" action="{{ route('barang.store') }}">
      @csrf
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kode SKU / Barcode</label>
          <input class="form-control" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: OLI-10W40-01">
          @error('kode_barang')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Nama Barang *</label>
          <input class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" required placeholder="Contoh: Oli Mesin 10W-40">
          @error('nama_barang')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Kategori *</label>
          <select class="form-control" name="kategori" required>
            @foreach(['Suku Cadang','Alat Kerja','Bahan Habis Pakai'] as $k)
              <option value="{{ $k }}" {{ old('kategori')===$k?'selected':'' }}>{{ $k }}</option>
            @endforeach
          </select>
          @error('kategori')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Stok Awal *</label>
          <input class="form-control" name="stok" type="number" min="0" required value="{{ old('stok',0) }}" placeholder="0">
          @error('stok')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Batas Stok Minimum * <span class="text-muted">(Peringatan)</span></label>
          <input class="form-control" name="stok_minimum" type="number" min="1" required value="{{ old('stok_minimum',5) }}" placeholder="5">
          @error('stok_minimum')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Harga Satuan (Rp) *</label>
          <input class="form-control" name="harga_satuan" type="number" min="0" required value="{{ old('harga_satuan') }}" placeholder="85000">
          @error('harga_satuan')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="form-actions">
        <a href="{{ route('barang.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Simpan Barang
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
