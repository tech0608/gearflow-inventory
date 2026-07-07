@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Selamat Datang, {{ session('pengguna.nama') }} 👋</h2>
    <p>Ringkasan kondisi inventaris bengkel hari ini</p>
  </div>
</div>

@if(isset($kritisItems) && $kritisItems->count() > 0)
<div class="alert alert-danger" style="margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; border-left:4px solid var(--danger)">
  <div style="display:flex; align-items:center; gap:10px">
    <span style="font-size:1.5rem">⚠️</span>
    <div>
      <strong style="font-size:0.95rem; display:block">Perhatian: Ada {{ $kritisItems->count() }} Barang dengan Stok Kritis / Habis!</strong>
      <span style="font-size:0.8rem; color:var(--text-secondary)">
        {{ $kritisItems->pluck('nama_barang')->take(3)->implode(', ') }}
        {{ $kritisItems->count() > 3 ? '... dan lainnya' : '' }}
      </span>
    </div>
  </div>
  <a href="{{ route('barang-masuk.create') }}" class="btn btn-success btn-sm" style="white-space:nowrap; padding:6px 12px">
    📦 Tambah Stok Sekarang →
  </a>
</div>
@endif

{{-- Stat Cards --}}
<div class="stats-grid">
  <div class="stat-card blue">
    <div class="stat-card-top">
      <div>
        <div class="stat-value">{{ $stats['total_barang'] }}</div>
        <div class="stat-label">Total Barang</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Jenis barang terdaftar</div>
  </div>

  <div class="stat-card green">
    <div class="stat-card-top">
      <div>
        <div class="stat-value">{{ $stats['masuk_hari_ini'] }}</div>
        <div class="stat-label">Masuk Hari Ini</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="8 17 12 21 16 17"/><line x1="12" y1="21" x2="12" y2="7"/>
          <path d="M3 7l9-4 9 4"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Transaksi masuk</div>
  </div>

  <div class="stat-card orange">
    <div class="stat-card-top">
      <div>
        <div class="stat-value">{{ $stats['keluar_hari_ini'] }}</div>
        <div class="stat-label">Keluar Hari Ini</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="16 7 12 3 8 7"/><line x1="12" y1="3" x2="12" y2="17"/>
          <path d="M3 17l9 4 9-4"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Transaksi keluar</div>
  </div>

  <div class="stat-card red">
    <div class="stat-card-top">
      <div>
        <div class="stat-value">{{ $stats['stok_kritis'] }}</div>
        <div class="stat-label">Stok Kritis</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Stok ≤ 5 unit</div>
  </div>

  <div class="stat-card cyan">
    <div class="stat-card-top">
      <div>
        <div class="stat-value">{{ $stats['total_pemasok'] }}</div>
        <div class="stat-label">Pemasok</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
          <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Mitra pemasok aktif</div>
  </div>

  <div class="stat-card purple">
    <div class="stat-card-top">
      <div>
        <div class="stat-value" style="font-size:1.2rem">Rp {{ number_format($stats['nilai_inventaris'],0,',','.') }}</div>
        <div class="stat-label">Nilai Inventaris</div>
      </div>
      <div class="stat-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
    </div>
    <div class="stat-sub">Total nilai stok saat ini</div>
  </div>
</div>

{{-- Dynamic Chart.js Analytics Section --}}
<div class="dashboard-grid" style="margin-bottom:20px">
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="card-title-dot" style="background:var(--accent)"></div>
        Tren Transaksi (7 Hari Terakhir)
      </div>
    </div>
    <div class="card-body" style="padding:16px; height:260px">
      <canvas id="trendChart"></canvas>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="card-title-dot" style="background:#a78bfa"></div>
        Komposisi Stok per Kategori
      </div>
    </div>
    <div class="card-body" style="padding:16px; height:260px; display:flex; justify-content:center">
      <canvas id="kategoriChart"></canvas>
    </div>
  </div>
</div>

<div class="dashboard-grid">
  {{-- Recent Masuk --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="card-title-dot" style="background:var(--success)"></div>
        Barang Masuk Terkini
      </div>
      <a href="{{ route('barang-masuk.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:8px 20px">
      @forelse($recentMasuk as $m)
        <div class="recent-item">
          <div class="recent-dot" style="background:var(--success)"></div>
          <div class="recent-info">
            <div class="recent-name">{{ $m->barang->nama_barang ?? '-' }}</div>
            <div class="recent-meta">{{ $m->pemasok->nama_pemasok ?? '-' }} · {{ $m->tanggal_masuk->format('d M Y') }}</div>
          </div>
          <div class="recent-qty" style="color:var(--success)">+{{ number_format($m->jumlah_masuk) }}</div>
        </div>
      @empty
        <p class="text-muted text-sm" style="padding:20px 0">Belum ada transaksi masuk hari ini</p>
      @endforelse
    </div>
  </div>

  {{-- Recent Keluar --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="card-title-dot" style="background:var(--warning)"></div>
        Barang Keluar Terkini
      </div>
      <a href="{{ route('barang-keluar.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding:8px 20px">
      @forelse($recentKeluar as $k)
        <div class="recent-item">
          <div class="recent-dot" style="background:var(--warning)"></div>
          <div class="recent-info">
            <div class="recent-name">{{ $k->barang->nama_barang ?? '-' }}</div>
            <div class="recent-meta">{{ $k->tujuan ?? '-' }} · {{ $k->tanggal_keluar->format('d M Y') }}</div>
          </div>
          <div class="recent-qty" style="color:var(--warning)">-{{ number_format($k->jumlah_keluar) }}</div>
        </div>
      @empty
        <p class="text-muted text-sm" style="padding:20px 0">Belum ada transaksi keluar</p>
      @endforelse
    </div>
  </div>

  {{-- Stok Monitor --}}
  <div class="card" style="grid-column:1/-1">
    <div class="card-header">
      <div class="card-title">
        <div class="card-title-dot" style="background:var(--warning)"></div>
        Monitor Stok Barang
      </div>
      <a href="{{ route('barang.index') }}" class="btn btn-ghost btn-sm">Kelola Barang</a>
    </div>
    <div class="card-body" style="padding:8px 20px">
      @forelse($stokMonitor as $b)
        @php
          $pct = min(100, round($b->stok / $maxStok * 100));
          $col = $b->stok <= 5 ? 'var(--danger)' : ($b->stok <= 15 ? 'var(--warning)' : 'var(--success)');
        @endphp
        <div class="stok-bar-wrap">
          <div class="stok-bar-info">
            <div class="stok-bar-name">{{ $b->nama_barang }}</div>
            <div class="stok-bar-val">
              <span class="badge {{ $b->kategori === 'Suku Cadang' ? 'badge-blue' : ($b->kategori === 'Alat Kerja' ? 'badge-purple' : 'badge-orange') }}">
                {{ $b->kategori }}
              </span>
            </div>
          </div>
          <div class="stok-bar-outer" style="width:120px">
            <div class="stok-bar-inner" style="width:{{ $pct }}%;background:{{ $col }}"></div>
          </div>
          <span class="badge" style="background:{{ $col }}22;color:{{ $col }};min-width:60px;justify-content:center">
            {{ $b->stok }} unit
          </span>
        </div>
      @empty
        <p class="text-muted text-sm" style="padding:20px 0">✅ Semua stok dalam kondisi baik</p>
      @endforelse
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ── 1. Line Chart: Tren Transaksi ──
  const ctxTrend = document.getElementById('trendChart').getContext('2d');
  new Chart(ctxTrend, {
    type: 'line',
    data: {
      labels: @json($chartDates),
      datasets: [
        {
          label: 'Barang Masuk',
          data: @json($chartMasuk),
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.3,
          fill: true,
          pointRadius: 4
        },
        {
          label: 'Barang Keluar',
          data: @json($chartKeluar),
          borderColor: '#f59e0b',
          backgroundColor: 'rgba(245, 158, 11, 0.1)',
          tension: 0.3,
          fill: true,
          pointRadius: 4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { labels: { color: '#94a3b8', font: { size: 11 } } }
      },
      scales: {
        x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } },
        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8', stepSize: 5 } }
      }
    }
  });

  // ── 2. Doughnut Chart: Komposisi Stok ──
  const ctxKat = document.getElementById('kategoriChart').getContext('2d');
  new Chart(ctxKat, {
    type: 'doughnut',
    data: {
      labels: @json($kategoriLabels),
      datasets: [{
        data: @json($kategoriValues),
        backgroundColor: ['#6366f1', '#a78bfa', '#fbbf24'],
        borderWidth: 0,
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'right', labels: { color: '#94a3b8', font: { size: 11 }, padding: 12 } }
      },
      cutout: '70%'
    }
  });
});
</script>
@endsection
