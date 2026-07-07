@extends('layouts.app')
@section('title','Tambah Pemasok')
@section('page-title','Tambah Pemasok')
@section('content')
<div class="page-header">
  <div class="page-header-left"><h2>Tambah Pemasok</h2><p>Daftarkan mitra pemasok baru</p></div>
  <a href="{{ route('pemasok.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('pemasok.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Pemasok *</label>
        <input class="form-control" name="nama_pemasok" value="{{ old('nama_pemasok') }}" required placeholder="PT / CV / UD ...">
        @error('nama_pemasok')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Kontak (Telepon / Email)</label>
        <input class="form-control" name="kontak" value="{{ old('kontak') }}" placeholder="021-xxx atau email@domain.com">
        @error('kontak')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Alamat</label>
        <input class="form-control" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. ...">
        @error('alamat')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-actions">
        <a href="{{ route('pemasok.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Simpan Pemasok
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
