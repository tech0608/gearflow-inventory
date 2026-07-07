@extends('layouts.app')
@section('title','Data Pemasok')
@section('page-title','Data Pemasok')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Data Pemasok</h2>
    <p>Kelola mitra pemasok barang bengkel</p>
  </div>
</div>
<div class="card">
  @include('partials._table-header',[
    'route'       => 'pemasok.create',
    'label'       => 'Tambah Pemasok',
    'q'           => $q,
    'placeholder' => 'Cari nama, kontak, atau alamat...',
  ])
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>Nama Pemasok</th><th>Kontak</th><th>Alamat</th><th>Aksi</th>
      </tr></thead>
      <tbody>
      @forelse($pemasoks as $i => $p)
        <tr>
          <td class="text-muted text-sm">{{ $pemasoks->firstItem() + $i }}</td>
          <td><strong>{{ $p->nama_pemasok }}</strong></td>
          <td>{{ $p->kontak ?? '-' }}</td>
          <td class="text-sm text-muted">{{ $p->alamat ?? '-' }}</td>
          <td>
            <div class="actions-cell">
              <a href="{{ route('pemasok.edit',$p) }}" class="btn btn-icon btn-edit btn-sm" title="Edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              <form method="POST" action="{{ route('pemasok.destroy',$p) }}"
                    onsubmit="return confirm('Hapus pemasok {{ $p->nama_pemasok }}?')">
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
        <tr><td colspan="5"><div class="empty-state"><p>Belum ada data pemasok</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $pemasoks->firstItem() ?? 0 }}–{{ $pemasoks->lastItem() ?? 0 }} dari {{ $pemasoks->total() }} data</span>
    <div>{{ $pemasoks->appends(['q'=>$q])->links('partials._pagination') }}</div>
  </div>
</div>
@endsection
