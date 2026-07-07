@extends('layouts.app')
@section('title','Tambah Pengguna')
@section('page-title','Tambah Pengguna')
@section('content')
<div class="page-header">
  <div class="page-header-left"><h2>Tambah Pengguna</h2><p>Buat akun pengguna baru untuk mengakses sistem</p></div>
  <a href="{{ route('pengguna.index') }}" class="btn btn-ghost">← Kembali</a>
</div>
<div class="card" style="max-width:560px">
  <div class="card-body">
    <form method="POST" action="{{ route('pengguna.store') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Lengkap *</label>
        <input class="form-control" name="nama_pengguna" value="{{ old('nama_pengguna') }}" required placeholder="Nama lengkap pengguna">
        @error('nama_pengguna')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Username *</label>
          <input class="form-control" name="username" value="{{ old('username') }}" required
                 placeholder="huruf & angka saja" autocomplete="off">
          @error('username')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Role *</label>
          <select class="form-control" name="role" required>
            <option value="staf" {{ old('role')==='staf'?'selected':'' }}>Staf</option>
            <option value="admin" {{ old('role')==='admin'?'selected':'' }}>Admin</option>
          </select>
          @error('role')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- ── Email untuk Notifikasi ────────────────────── --}}
      <div class="form-group">
        <label class="form-label">
          Email
          <span class="text-muted">(untuk notifikasi stok kritis – opsional)</span>
        </label>
        <input class="form-control" type="email" name="email" value="{{ old('email') }}"
               placeholder="contoh@email.com" autocomplete="off">
        @error('email')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        <div class="form-hint">
          📧 Jika diisi dan role adalah <strong>Admin</strong>, email notifikasi stok kritis akan dikirim ke alamat ini.
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Password * <span class="text-muted">(min. 8 karakter)</span></label>
          <input class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
          @error('password')<div class="form-error" style="display:block">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Konfirmasi Password *</label>
          <input class="form-control" type="password" name="password_confirmation" required placeholder="••••••••">
        </div>
      </div>
      <div class="form-hint" style="margin-bottom:16px">
        🔒 Password akan dienkripsi dengan <strong>bcrypt</strong> sebelum disimpan.
      </div>
      <div class="form-actions">
        <a href="{{ route('pengguna.index') }}" class="btn btn-ghost">Batal</a>
        <button type="submit" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
          </svg>
          Buat Pengguna
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
