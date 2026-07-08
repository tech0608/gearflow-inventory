<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') – Inventaris Bengkel</title>
  <meta name="description" content="Sistem Inventaris Barang Bengkel – Kelola stok, transaksi, dan laporan dengan aman.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2.0">
  <script>
    if (localStorage.getItem('theme') === 'light') {
      document.documentElement.classList.add('light-mode');
    }
  </script>
</head>
<body>

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
      </svg>
    </div>
    <div class="brand-text">
      <span class="brand-name">InvBengkel</span>
      <span class="brand-sub">Laravel {{ app()->version() }}</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
      <span>Dashboard</span>
    </a>
    <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
      </svg>
      <span>Data Barang</span>
    </a>
    <a class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}" href="{{ route('barang-masuk.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="8 17 12 21 16 17"/><line x1="12" y1="21" x2="12" y2="7"/>
        <path d="M3 7l9-4 9 4"/>
      </svg>
      <span>Barang Masuk</span>
    </a>
    <a class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}" href="{{ route('barang-keluar.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="16 7 12 3 8 7"/><line x1="12" y1="3" x2="12" y2="17"/>
        <path d="M3 17l9 4 9-4"/>
      </svg>
      <span>Barang Keluar</span>
    </a>
    <a class="nav-link {{ request()->routeIs('pemasok.*') ? 'active' : '' }}" href="{{ route('pemasok.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="1" y="3" width="15" height="13"/>
        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
      </svg>
      <span>Pemasok</span>
    </a>
    @if(session('pengguna.role') === 'admin')
    <a class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
      <span>Pengguna</span>
      <span class="nav-badge">Admin</span>
    </a>
    @endif
    <div class="nav-divider"></div>
    <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span>Laporan</span>
    </a>
    <a class="nav-link {{ request()->routeIs('activity-log.*') ? 'active' : '' }}" href="{{ route('activity-log.index') }}">
      <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
      </svg>
      <span>Log Aktivitas</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar">{{ strtoupper(substr(session('pengguna.nama', 'A'), 0, 1)) }}</div>
      <div class="user-info">
        <span class="user-name">{{ session('pengguna.nama', 'Admin') }}</span>
        <span class="user-role">{{ ucfirst(session('pengguna.role', 'staf')) }}</span>
      </div>
    </div>
  </div>
</aside>

{{-- ===== MAIN WRAPPER ===== --}}
<div class="main-wrapper" id="main-wrapper">
  <header class="topbar">
    <button class="sidebar-toggle" id="sidebar-toggle">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
    <div class="topbar-title">
      <h1>@yield('page-title', 'Dashboard')</h1>
    </div>
    <div class="topbar-right" style="gap:16px">
      <form method="GET" action="{{ route('barang.index') }}" class="no-print" style="margin:0;display:flex;align-items:center">
        <input type="text" name="q" placeholder="⚡ Cari Cepat SKU/Barang..." class="form-control" style="padding:6px 14px;border-radius:20px;font-size:0.8rem;width:200px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#fff">
      </form>
      <span class="topbar-date no-print">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
      <button id="theme-toggle" class="btn btn-ghost btn-sm no-print" title="Ganti Tema" style="padding: 8px; border-radius: 50%; min-width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; margin: 0;">
        <svg id="theme-toggle-light-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
        </svg>
        <svg id="theme-toggle-dark-icon" style="display:none" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
      </button>
      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="btn btn-ghost btn-sm" title="Logout">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
          Logout
        </button>
      </form>
    </div>
  </header>

  <main class="content">
    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="alert alert-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
        {{ session('error') }}
      </div>
    @endif

    @yield('content')
  </main>
</div>

<script>
  // Sidebar toggle
  const toggle = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('sidebar');
  const wrapper = document.getElementById('main-wrapper');
  toggle?.addEventListener('click', () => {
    if (window.innerWidth <= 768) {
      sidebar.classList.toggle('mobile-open');
    } else {
      sidebar.classList.toggle('collapsed');
      wrapper.classList.toggle('sidebar-collapsed');
    }
  });
  // Auto-dismiss alerts
  document.querySelectorAll('.alert').forEach(a => {
    setTimeout(() => { a.style.opacity = '0'; setTimeout(() => a.remove(), 400); }, 4000);
  });

  // Theme Toggle
  const themeToggle = document.getElementById('theme-toggle');
  const lightIcon = document.getElementById('theme-toggle-light-icon');
  const darkIcon = document.getElementById('theme-toggle-dark-icon');

  function updateToggleIcons() {
    if (document.documentElement.classList.contains('light-mode')) {
      lightIcon.style.display = 'none';
      darkIcon.style.display = 'block';
    } else {
      lightIcon.style.display = 'block';
      darkIcon.style.display = 'none';
    }
  }

  updateToggleIcons();

  themeToggle?.addEventListener('click', () => {
    if (document.documentElement.classList.contains('light-mode')) {
      document.documentElement.classList.remove('light-mode');
      localStorage.setItem('theme', 'dark');
    } else {
      document.documentElement.classList.add('light-mode');
      localStorage.setItem('theme', 'light');
    }
    updateToggleIcons();
  });
</script>
</body>
</html>
