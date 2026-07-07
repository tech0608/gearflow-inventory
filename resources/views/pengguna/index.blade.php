@extends('layouts.app')
@section('title','Data Pengguna')
@section('page-title','Data Pengguna')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Data Pengguna</h2>
    <p>Kelola akun pengguna sistem (Admin Only)</p>
  </div>
</div>
<div class="card">
  @include('partials._table-header',[
    'route'       => 'pengguna.create',
    'label'       => 'Tambah Pengguna',
    'q'           => $q,
    'placeholder' => 'Cari nama atau username...',
  ])
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>Nama Lengkap</th><th>Username</th><th>Role</th><th>Aksi</th>
      </tr></thead>
      <tbody>
      @forelse($penggunas as $i => $u)
        <tr>
          <td class="text-muted text-sm">{{ $penggunas->firstItem() + $i }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="user-avatar" style="width:30px;height:30px;font-size:0.8rem;flex-shrink:0">
                {{ strtoupper(substr($u->nama_pengguna,0,1)) }}
              </div>
              <strong>{{ $u->nama_pengguna }}</strong>
            </div>
          </td>
          <td><code style="color:var(--accent);font-size:0.85rem">{{ $u->username }}</code></td>
          <td>
            @if($u->role === 'admin')
              <span class="badge badge-blue">Admin</span>
            @else
              <span class="badge badge-gray">Staf</span>
            @endif
          </td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('pengguna.edit',$u) }}" class="btn btn-icon btn-edit btn-sm" title="Edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              <form method="POST" action="{{ route('pengguna.destroy',$u) }}"
                    onsubmit="return confirm('Hapus pengguna {{ $u->nama_pengguna }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-icon btn-delete btn-sm" title="Hapus">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                  </svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5"><div class="empty-state"><p>Belum ada pengguna</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $penggunas->firstItem() ?? 0 }}–{{ $penggunas->lastItem() ?? 0 }} dari {{ $penggunas->total() }} data</span>
    <div>{{ $penggunas->appends(['q'=>$q])->links('partials._pagination') }}</div>
  </div>
</div>
@endsection
