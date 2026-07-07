<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 – Halaman Tidak Ditemukan | Inventaris Bengkel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; font-family:'Inter',sans-serif; }
    .error-wrap { text-align:center; max-width:460px; padding:40px 20px; }
    .error-code { font-size:7rem; font-weight:800; line-height:1;
      background:linear-gradient(135deg,#3b82f6,#06b6d4); -webkit-background-clip:text;
      -webkit-text-fill-color:transparent; background-clip:text; margin-bottom:0; }
    .error-icon { font-size:3rem; margin-bottom:12px; }
    .error-title { font-size:1.5rem; font-weight:700; margin-bottom:10px; }
    .error-desc  { color:var(--text-secondary); margin-bottom:28px; line-height:1.6; }
    .error-actions { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
  </style>
</head>
<body>
<div class="error-wrap">
  <div class="error-icon">🔍</div>
  <div class="error-code">404</div>
  <div class="error-title">Halaman Tidak Ditemukan</div>
  <p class="error-desc">
    Halaman yang Anda cari tidak ada atau telah dipindahkan.<br>
    Periksa kembali URL yang Anda masukkan.
  </p>
  <div class="error-actions">
    <a href="{{ url()->previous() }}" class="btn btn-ghost">← Kembali</a>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">🏠 Dashboard</a>
  </div>
  <p style="margin-top:24px;font-size:0.75rem;color:var(--text-muted)">
    Inventaris Bengkel – Universitas Teknologi Bandung
  </p>
</div>
</body>
</html>
