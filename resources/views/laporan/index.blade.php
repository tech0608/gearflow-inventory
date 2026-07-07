@extends('layouts.app')
@section('title','Laporan Transaksi')
@section('page-title','Laporan Transaksi')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Laporan Transaksi</h2>
    <p>Riwayat lengkap barang masuk dan keluar</p>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('export.laporan', ['tipe' => $tipe, 'dari' => $dari, 'sampai' => $sampai]) }}" class="btn btn-ghost no-print">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Export Excel (.csv)
    </a>
    <button onclick="window.print()" class="btn btn-ghost no-print">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 6 2 18 2 18 9"/>
        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
        <rect x="6" y="14" width="12" height="8"/>
      </svg>
      Cetak Laporan
    </button>
  </div>
</div>

{{-- Filter --}}
<div class="card no-print" style="margin-bottom:20px">
  <div class="card-body">
    <form method="GET" action="{{ route('laporan.index') }}">
      <div class="laporan-filter">
        <div class="form-group">
          <label class="form-label">Jenis Transaksi</label>
          <select class="form-control" name="tipe">
            <option value="all"    {{ $tipe==='all'?'selected':''    }}>Semua</option>
            <option value="masuk"  {{ $tipe==='masuk'?'selected':''  }}>Barang Masuk</option>
            <option value="keluar" {{ $tipe==='keluar'?'selected':'' }}>Barang Keluar</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Dari Tanggal</label>
          <input class="form-control" type="date" name="dari" value="{{ $dari }}">
        </div>
        <div class="form-group">
          <label class="form-label">Sampai Tanggal</label>
          <input class="form-control" type="date" name="sampai" value="{{ $sampai }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('laporan.index') }}" class="btn btn-ghost">Reset</a>
      </div>
    </form>

    {{-- Summary Badges --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:4px">
      <span class="badge badge-green" style="padding:8px 14px;font-size:0.82rem">
        ↑ Masuk: {{ number_format($totalMasuk) }} unit
        ({{ $transaksi->where('tipe','masuk')->count() }} transaksi)
      </span>
      <span class="badge badge-orange" style="padding:8px 14px;font-size:0.82rem">
        ↓ Keluar: {{ number_format($totalKeluar) }} unit
        ({{ $transaksi->where('tipe','keluar')->count() }} transaksi)
      </span>
      <span class="badge badge-blue" style="padding:8px 14px;font-size:0.82rem">
        Total: {{ $transaksi->count() }} transaksi
      </span>
    </div>
  </div>
</div>

<div class="card">
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>Jenis</th><th>Tanggal</th><th>Nama Barang</th>
        <th>Keterangan</th><th class="text-right">Jumlah</th>
      </tr></thead>
      <tbody>
      @forelse($transaksi as $i => $t)
        <tr>
          <td class="text-muted text-sm">{{ $i + 1 }}</td>
          <td>
            @if($t['tipe'] === 'masuk')
              <span class="badge badge-green">↑ Masuk</span>
            @else
              <span class="badge badge-orange">↓ Keluar</span>
            @endif
          </td>
          <td>{{ $t['tanggal'] instanceof \Carbon\Carbon ? $t['tanggal']->format('d M Y') : \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}</td>
          <td><strong>{{ $t['barang'] }}</strong></td>
          <td class="text-sm">{{ $t['keterangan'] }}</td>
          <td class="text-right num-cell {{ $t['tipe']==='masuk'?'stok-ok':'stok-warn' }}">
            {{ $t['tipe']==='masuk' ? '+' : '-' }}{{ number_format($t['jumlah']) }}
          </td>
        </tr>
      @empty
        <tr><td colspan="6"><div class="empty-state"><p>Tidak ada data untuk ditampilkan</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
