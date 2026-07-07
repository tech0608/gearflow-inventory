@extends('layouts.app')
@section('title','Log Aktivitas Sistem')
@section('page-title','Log Aktivitas Sistem')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Log Aktivitas Sistem (Audit Trail)</h2>
    <p>Riwayat aktivitas pengguna, keamanan, dan transaksi barang</p>
  </div>
</div>

<div class="card no-print" style="margin-bottom:20px">
  <div class="card-body">
    <form method="GET" action="{{ route('activity-log.index') }}">
      <div class="laporan-filter">
        <div class="form-group" style="flex:1;min-width:200px">
          <label class="form-label">Cari Aktivitas / User</label>
          <input class="form-control" name="q" value="{{ $q }}" placeholder="Cari deskripsi atau nama user...">
        </div>
        <div class="form-group">
          <label class="form-label">Tipe Aktivitas</label>
          <select class="form-control" name="action">
            <option value="all"    {{ $action==='all'?'selected':''    }}>Semua Tipe</option>
            <option value="LOGIN"  {{ $action==='LOGIN'?'selected':''  }}>LOGIN / LOGOUT</option>
            <option value="CREATE" {{ $action==='CREATE'?'selected':'' }}>CREATE (Tambah)</option>
            <option value="UPDATE" {{ $action==='UPDATE'?'selected':'' }}>UPDATE (Edit)</option>
            <option value="DELETE" {{ $action==='DELETE'?'selected':'' }}>DELETE (Hapus)</option>
            <option value="MASUK"  {{ $action==='MASUK'?'selected':''  }}>BARANG MASUK</option>
            <option value="KELUAR" {{ $action==='KELUAR'?'selected':'' }}>BARANG KELUAR</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('activity-log.index') }}" class="btn btn-ghost">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th>
        <th>Waktu</th>
        <th>Pengguna</th>
        <th>Tipe Aksi</th>
        <th>Deskripsi Aktivitas</th>
        <th>IP Address</th>
      </tr></thead>
      <tbody>
      @forelse($logs as $i => $log)
        @php
          $badges = [
            'LOGIN'  => 'badge-blue',
            'LOGOUT' => 'badge-gray',
            'CREATE' => 'badge-green',
            'UPDATE' => 'badge-purple',
            'DELETE' => 'badge-orange',
            'MASUK'  => 'badge-green',
            'KELUAR' => 'badge-orange',
            'SYSTEM' => 'badge-blue',
          ];
          $color = $badges[$log->action] ?? 'badge-gray';
        @endphp
        <tr>
          <td class="text-muted text-sm">{{ $logs->firstItem() + $i }}</td>
          <td class="text-sm" style="white-space:nowrap">{{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}</td>
          <td><strong>{{ $log->nama_user ?? 'Sistem' }}</strong></td>
          <td><span class="badge {{ $color }}">{{ $log->action }}</span></td>
          <td>{{ $log->description }}</td>
          <td><code style="font-size:0.75rem;color:var(--text-muted)">{{ $log->ip_address ?? '-' }}</code></td>
        </tr>
      @empty
        <tr><td colspan="6"><div class="empty-state"><p>Belum ada catatan aktivitas</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data</span>
    <div>{{ $logs->appends(['q'=>$q, 'action'=>$action])->links('partials._pagination') }}</div>
  </div>
</div>
@endsection
