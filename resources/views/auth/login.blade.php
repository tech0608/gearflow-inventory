<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – Inventaris Bengkel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; overflow:auto; }
    .login-wrap { width:100%; max-width:420px; padding:20px; }
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

    <h3>Selamat Datang</h3>
    <p class="sub">Masuk untuk mengelola inventaris bengkel Anda</p>

    @if($errors->any())
      <div class="alert alert-danger" style="margin-bottom:16px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username" id="username"
          value="{{ old('username') }}" required autofocus autocomplete="username"
          placeholder="Masukkan username" />
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div style="position:relative">
          <input class="form-control" type="password" name="password" id="password"
            required autocomplete="current-password" placeholder="••••••••"
            style="padding-right:44px" />
          <button type="button" id="toggle-pw" style="
            position:absolute;right:12px;top:50%;transform:translateY(-50%);
            background:none;border:none;cursor:pointer;color:var(--text-muted);padding:0
          ">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-full" style="margin-top:8px;padding:12px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        Masuk ke Sistem
      </button>

      <div style="text-align:center;margin-top:18px;font-size:0.82rem;color:var(--text-muted)">
        🔒 Akses hanya untuk pengguna terdaftar.<br>Hubungi Admin untuk mendapatkan akun.
      </div>
    </form>

    <div class="secure-badges">
      <span class="sec-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        CSRF Protected
      </span>
      <span class="sec-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Bcrypt Password
      </span>
      <span class="sec-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Login Throttle
      </span>
    </div>
  </div>
  <div class="login-footer">
    Universitas Teknologi Bandung – Teknik Informatika © 2026
  </div>
</div>
<script>
  document.getElementById('toggle-pw')?.addEventListener('click', function() {
    const inp = document.getElementById('password');
    inp.type = inp.type === 'password' ? 'text' : 'password';
  });
</script>
</body>
</html>
