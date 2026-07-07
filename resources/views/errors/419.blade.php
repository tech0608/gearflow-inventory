<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>419 – Sesi Kadaluarsa | Inventaris Bengkel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; font-family:'Inter',sans-serif; }
    .error-wrap { text-align:center; max-width:460px; padding:40px 20px; }
    .error-code { font-size:7rem; font-weight:800; line-height:1;
      background:linear-gradient(135deg,#8b5cf6,#3b82f6); -webkit-background-clip:text;
      -webkit-text-fill-color:transparent; background-clip:text; margin-bottom:0; }
    .error-icon { font-size:3rem; margin-bottom:12px; }
    .error-title { font-size:1.5rem; font-weight:700; margin-bottom:10px; }
    .error-desc  { color:var(--text-secondary); margin-bottom:28px; line-height:1.6; }
    .error-actions { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
  </style>
</head>
<body>
<div class="error-wrap">
  <div class="error-icon">⏰</div>
  <div class="error-code">419</div>
  <div class="error-title">Sesi Kadaluarsa</div>
  <p class="error-desc">
    Token keamanan Anda telah kadaluarsa (CSRF token expired).<br>
    Silakan muat ulang halaman dan coba lagi.
  </p>
  <div class="error-actions">
    <a href="{{ url()->previous() }}" class="btn btn-ghost" onclick="history.go(-1); return false;">↺ Muat Ulang</a>
    <a href="{{ route('login') }}" class="btn btn-primary">🔑 Login Ulang</a>
  </div>
  <p style="margin-top:24px;font-size:0.75rem;color:var(--text-muted)">
    Inventaris Bengkel – Universitas Teknologi Bandung
  </p>
</div>
</body>
</html>
