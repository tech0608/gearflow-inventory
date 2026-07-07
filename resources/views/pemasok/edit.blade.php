@extends('layouts.app')
@section('title','Edit Pemasok')
@section('page-title','Edit Pemasok')
@section('content')
<div class="page-header">
  <div class="page-header-left"><h2>Edit Pemasok</h2><p>Perbarui data: <strong>{{ $pemasok->nama_pemasok }}</strong></p></div>
  <a href="{{ route('pemasok.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('pemasok.update',$pemasok) }}">
      @csrf @method('PUT')
      <div class="form-group">
        <label class="form-label">Nama Pemasok *</label>
        <input class="form-control" name="nama_pemasok" value="{{ old('nama_pemasok',$pemasok->nama_pemasok) }}" required>
        @error('nama_pemasok')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Kontak</label>
        <input class="form-control" name="kontak" value="{{ old('kontak',$pemasok->kontak) }}">
        @error('kontak')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Alamat</label>
        <input class="form-control" name="alamat" value="{{ old('alamat',$pemasok->alamat) }}">
        @error('alamat')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-actions">
        <a href="{{ route('pemasok.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Perbarui Pemasok
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
