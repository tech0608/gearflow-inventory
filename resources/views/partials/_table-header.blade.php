{{-- Partial: _table-header.blade.php --}}
{{-- Usage: @include('partials._table-header', ['route'=>'barang.create','label'=>'Tambah Barang','color'=>'btn-primary']) --}}
<div class="card-header">
  <div class="toolbar">
    <form method="GET" style="display:contents">
      <div class="search-box">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" name="q" value="{{ $q ?? '' }}" placeholder="{{ $placeholder ?? 'Cari...' }}" />
      </div>
      <button type="submit" class="btn btn-ghost btn-sm">Cari</button>
    </form>
  </div>
  @if(isset($route))
  <a href="{{ route($route) }}" class="btn {{ $color ?? 'btn-primary' }}">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
    {{ $label ?? 'Tambah' }}
  </a>
  @endif
</div>
