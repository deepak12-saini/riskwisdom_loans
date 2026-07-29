@if ($paginator->total() > 0)
    <nav class="rw-pager" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <p class="rw-pager__summary">
            Showing
            <strong>{{ $paginator->firstItem() }}</strong>
            to
            <strong>{{ $paginator->lastItem() }}</strong>
            of
            <strong>{{ number_format($paginator->total()) }}</strong>
            @if ($paginator->lastPage() > 1)
                · Page <strong>{{ $paginator->currentPage() }}</strong> of <strong>{{ $paginator->lastPage() }}</strong>
            @endif
        </p>

        <ul class="rw-pager__pages">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="rw-pager__btn is-disabled" aria-disabled="true">Previous</span>
                @else
                    <a class="rw-pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                @endif
            </li>

            @if ($paginator->hasPages())
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="rw-pager__btn is-ellipsis" aria-hidden="true">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li>
                                @if ($page == $paginator->currentPage())
                                    <span class="rw-pager__btn is-current" aria-current="page">{{ $page }}</span>
                                @else
                                    <a class="rw-pager__btn" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach
            @else
                <li>
                    <span class="rw-pager__btn is-current" aria-current="page">1</span>
                </li>
            @endif

            <li>
                @if ($paginator->hasMorePages())
                    <a class="rw-pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                @else
                    <span class="rw-pager__btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
