<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar – Inventaris Bengkel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; overflow:auto; padding: 20px 0; }
    .login-wrap { width:100%; max-width:440px; padding:20px; }
    .login-card {
      background: var(--bg-card);
      border: 1px solid var(--border-strong);
      border-radius: var(--radius-xl);
      padding: 40px 36px;
      box-shadow: var(--shadow-xl);
    }
    .login-logo {
      display:flex; align-items:center; gap:12px;
      margin-bottom: 32px; justify-content:center;
    }
    .login-logo-icon {
      width:48px; height:48px;
      background: linear-gradient(135deg, var(--accent), #7c3aed);
      border-radius: var(--radius-lg);
      display:flex; align-items:center; justify-content:center;
      box-shadow: 0 0 24px var(--accent-glow);
    }
    .login-logo-text h2 { font-size:1.3rem; font-weight:800; }
    .login-logo-text p  { font-size:0.75rem; color:var(--text-secondary); }
    .login-card h3 { font-size:1.1rem; font-weight:700; margin-bottom:6px; }
    .login-card .sub { font-size:0.82rem; color:var(--text-secondary); margin-bottom:28px; }
    .login-footer { text-align:center; margin-top:20px; font-size:0.78rem; color:var(--text-muted); }
    .secure-badges { display:flex; gap:8px; justify-content:center; flex-wrap:wrap; margin-top:16px; }
    .sec-badge { display:flex; align-items:center; gap:4px; font-size:0.7rem; color:var(--text-muted); }
    .sec-badge svg { width:12px; height:12px; color:var(--success); }
    .role-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
    .role-btn {
      border: 1px solid var(--border);
      background: var(--bg-input);
      color: var(--text-primary);
      padding: 10px;
      border-radius: var(--radius);
      cursor: pointer;
      text-align: center;
      transition: var(--transition);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .role-btn:hover { border-color: var(--border-strong); }
    .role-input:checked + .role-btn {
      border-color: var(--accent);
      background: var(--accent-light);
      color: var(--accent);
    }
    .role-input { display: none; }
  </style>
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">
      <div class="login-logo-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
      <div class="login-logo-text">
        <h2>InvBengkel</h2>
        <p>Sistem Inventaris Barang</p>
      </div>
    </div>

    <h3>Daftar Akun Baru</h3>
    <p class="sub">Registrasi pengguna baru untuk mengelola sistem</p>

    @if($errors->any())
      <div class="alert alert-danger" style="margin-bottom:16px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
      @csrf
      
      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input class="form-control" type="text" name="nama_pengguna" id="nama_pengguna"
          value="{{ old('nama_pengguna') }}" required autofocus
          placeholder="Masukkan nama lengkap" />
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username" id="username"
          value="{{ old('username') }}" required
          placeholder="Masukkan username unik" />
      </div>

      <div class="form-group">
        <label class="form-label">Role Pengguna</label>
        <div class="role-grid">
          <label>
            <input type="radio" name="role" value="staf" class="role-input" checked>
            <div class="role-btn">
              <strong>User / Petugas</strong>
              <span style="font-size:0.72rem;color:var(--text-secondary)">Akses Transaksi</span>
            </div>
          </label>
          <label>
            <input type="radio" name="role" value="admin" class="role-input">
            <div class="role-btn">
              <strong>Administrator</strong>
              <span style="font-size:0.72rem;color:var(--text-secondary)">Kontrol Penuh</span>
            </div>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" id="password"
          required placeholder="Minimal 8 karakter" />
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
          required placeholder="Masukkan ulang password" />
      </div>

      <button type="submit" class="btn btn-primary w-full" style="margin-top:8px;padding:12px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="8.5" cy="7" r="4"/>
          <line x1="20" y1="8" x2="20" y2="14"/>
          <line x1="23" y1="11" x2="17" y2="11"/>
        </svg>
        Daftar Akun
      </button>

      <div style="text-align:center;margin-top:18px;font-size:0.85rem">
        <span class="text-muted">Sudah memiliki akun?</span>
        <a href="{{ route('login') }}" style="color:var(--accent);font-weight:600;margin-left:4px">Masuk</a>
      </div>
    </form>
  </div>
  <div class="login-footer">
    Universitas Teknologi Bandung – Teknik Informatika © 2026
  </div>
</div>
</body>
</html>
