@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="pagination__link pagination__link--disabled" aria-disabled="true">‹ Précédent</span>
        @else
            <a class="pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Précédent</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pagination__dots">{{ $element }}</span>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page === $paginator->currentPage())
                        <span class="pagination__link pagination__link--active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination__link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant ›</a>
        @else
            <span class="pagination__link pagination__link--disabled" aria-disabled="true">Suivant ›</span>
        @endif
    </nav>
@endif
