@extends('layouts.app')
@section('title','Barang Masuk')
@section('page-title','Barang Masuk')
@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h2>Barang Masuk</h2>
    <p>Pencatatan penerimaan barang dari pemasok</p>
  </div>
</div>
<div class="card">
  @include('partials._table-header',[
    'route'       => 'barang-masuk.create',
    'label'       => 'Catat Masuk',
    'color'       => 'btn-success',
    'q'           => $q,
    'placeholder' => 'Cari barang atau pemasok...',
  ])
  <div class="table-wrapper">
    <table class="data-table">
      <thead><tr>
        <th>#</th><th>Nama Barang</th><th>Kategori</th>
        <th>Pemasok</th><th>Tanggal Masuk</th>
        <th class="text-right">Jumlah</th><th>Aksi</th>
      </tr></thead>
      <tbody>
      @forelse($masuks as $i => $m)
        <tr>
          <td class="text-muted text-sm">{{ $masuks->firstItem() + $i }}</td>
          <td><strong>{{ $m->barang->nama_barang ?? '-' }}</strong></td>
          <td>
            @php $bc = ['Suku Cadang'=>'badge-blue','Alat Kerja'=>'badge-purple','Bahan Habis Pakai'=>'badge-orange']; $kat = $m->barang->kategori ?? ''; @endphp
            @if($kat)<span class="badge {{ $bc[$kat] ?? 'badge-gray' }}">{{ $kat }}</span>@endif
          </td>
          <td>{{ $m->pemasok->nama_pemasok ?? '-' }}</td>
          <td>{{ $m->tanggal_masuk->format('d M Y') }}</td>
          <td class="text-right">
            <span class="badge badge-green">+{{ number_format($m->jumlah_masuk) }} unit</span>
          </td>
          <td>
            <form method="POST" action="{{ route('barang-masuk.destroy',$m) }}"
                  onsubmit="return confirm('Hapus transaksi ini? Stok akan dikurangi kembali.')">
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
        <tr><td colspan="7"><div class="empty-state"><p>Belum ada transaksi barang masuk</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    <span>Menampilkan {{ $masuks->firstItem() ?? 0 }}–{{ $masuks->lastItem() ?? 0 }} dari {{ $masuks->total() }} data</span>
    <div>{{ $masuks->appends(['q'=>$q])->links('partials._pagination') }}</div>
  </div>
</div>
@endsection
