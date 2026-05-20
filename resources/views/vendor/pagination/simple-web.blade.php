@if ($paginator->hasPages())
  <nav class="web-pagination" aria-label="Pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <span class="disabled" aria-disabled="true">← {{ __('Préc.') }}</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev">← {{ __('Préc.') }}</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="disabled">{{ $element }}</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="active" aria-current="page">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Suiv.') }} →</a>
    @else
      <span class="disabled" aria-disabled="true">{{ __('Suiv.') }} →</span>
    @endif
  </nav>
@endif
