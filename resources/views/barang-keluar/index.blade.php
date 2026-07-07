@extends('layouts.app')
@section('title','Barang Keluar')
@section('page-title','Barang Keluar')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Barang Keluar</h2>
    <p>Pencatatan penggunaan atau penjualan barang dari bengkel</p>
  </div>
</div>
<div class="card">
  @include('partials._table-header',[
    'route'       => 'barang-keluar.create',
    'label'       => 'Catat Keluar',
    'color'       => 'btn-primary',
    'q'           => $q,
    'placeholder' => 'Cari barang atau tujuan...',
  ])
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>Nama Barang</th><th>Kategori</th>
        <th>Tanggal Keluar</th><th class="text-right">Jumlah</th>
        <th>Tujuan Penggunaan</th><th>Aksi</th>
      </tr></thead>
      <tbody>
      @forelse($keluars as $i => $k)
        @php $bc = ['Suku Cadang'=>'badge-blue','Alat Kerja'=>'badge-purple','Bahan Habis Pakai'=>'badge-orange']; $kat = $k->barang->kategori ?? ''; @endphp
        <tr>
          <td class="text-muted text-sm">{{ $keluars->firstItem() + $i }}</td>
          <td><strong>{{ $k->barang->nama_barang ?? '-' }}</strong></td>
          <td>@if($kat)<span class="badge {{ $bc[$kat] ?? 'badge-gray' }}">{{ $kat }}</span>@endif</td>
          <td>{{ $k->tanggal_keluar->format('d M Y') }}</td>
          <td class="text-right">
            <span class="badge badge-orange">-{{ number_format($k->jumlah_keluar) }} unit</span>
          </td>
          <td class="text-sm">{{ $k->tujuan ?? '-' }}</td>
          <td>
            <form method="POST" action="{{ route('barang-keluar.destroy',$k) }}"
                  onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-icon btn-delete btn-sm" title="Hapus">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                  <path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7"><div class="empty-state"><p>Belum ada transaksi barang keluar</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $keluars->firstItem() ?? 0 }}–{{ $keluars->lastItem() ?? 0 }} dari {{ $keluars->total() }} data</span>
    <div>{{ $keluars->appends(['q'=>$q])->links('partials._pagination') }}</div>
  </div>
</div>
@endsection
