{{-- このアプリ用の最小限のページネーション(Tailwind 等に依存しない) --}}
@if ($paginator->hasPages())
<nav>
    @if ($paginator->onFirstPage())
    <span class="font-gray">&laquo; 前へ</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; 前へ</a>
    @endif

    <span>{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>

    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next">次へ &raquo;</a>
    @else
    <span class="font-gray">次へ &raquo;</span>
    @endif
</nav>
@endif
