@if ($paginator->hasPages())
  <div style="display:flex;gap:4px;align-items:center;flex-wrap:wrap">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <button class="page-btn" disabled>&#8249;</button>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">&#8249;</a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="page-btn" style="cursor:default">…</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <button class="page-btn active">{{ $page }}</button>
          @else
            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">&#8250;</a>
    @else
      <button class="page-btn" disabled>&#8250;</button>
    @endif
  </div>
@endif
