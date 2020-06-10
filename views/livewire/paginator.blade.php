@if ($paginator->hasPages())
    @php
        $pageLink = ' role="button" '; //  href="#{!! $pageLink !!}" $paginator->getPageName();
        $current = $paginator->currentPage();
    @endphp
    <ul class="pagination" role="navigation">
        <li class="paginate_button page-item previous{{ $paginator->onFirstPage() ? " disabled" : ""}}">
            <button {!! $pageLink !!} {!! $paginator->onFirstPage() ? "" : 'wire:click="previousPage"' !!}
                    class="page-link">@lang('pagination.previous')</button>
        </li>

        <li class="paginate_button page-item {{$paginator->onFirstPage() ? 'active' : ''}}">
            <button {!! $pageLink !!} {!!$paginator->onFirstPage() ? '' : 'wire:click="gotoPage(1)"' !!} class="page-link">1</button>
        </li>

        @if($current > 3)
            <li class="paginate_button page-item disabled" id="results_ellipsis">
                <button {!! $pageLink !!} class="page-link">…</button>
            </li>
        @endif
        @if ( $paginator->hasMorePages() )
            @for ($page = max(2, $current - 1); $page < min($paginator->lastPage(), $current + 2); $page++)
            <li class="paginate_button page-item {{$page == $current ? ' active' : ''}}">
                <button {!! $pageLink !!} class="page-link" wire:click="gotoPage({{$page}})">{{$page}}</button>
            </li>
            @endfor

            @if($paginator->lastPage() - 2 > $current )
            <li class="paginate_button page-item disabled" id="results_ellipsis">
                <button {!! $pageLink !!} class="page-link">…</button>
            </li>
            @endif

            <li class="paginate_button page-item ">
                <button {!! $pageLink !!} class="page-link"
                    wire:click="gotoPage({{$paginator->lastPage()}})">{{$paginator->lastPage()}}</button>
            </li>
            <li class="paginate_button page-item next">
                <button {!! $pageLink !!} class="page-link" wire:click="nextPage">@lang('pagination.next')</button>
            </li>
        @else
            <li class="paginate_button page-item active">
                <button {!! $pageLink !!} class="page-link">{{$current}}</button>
            </li>
            <li class="paginate_button page-item next disabled">
                <button {!! $pageLink !!} class="page-link">@lang('pagination.next')</button>
            </li>
        @endif
    </ul>
@endif
