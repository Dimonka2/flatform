@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
    @endphp
    <ul class="pagination" role="navigation">
        <li class="paginate_button page-item previous{{ $paginator->onFirstPage() ? " disabled" : ""}}">
            <button wire:click="previousPage" class="page-link">@lang('pagination.previous')</button>
        </li>

        <li class="paginate_button page-item {{$paginator->onFirstPage() ? 'active' : ''}}">
            <button wire:click="gotoPage(1)" class="page-link">1</button>
        </li>

        @if($current > 3)
            <li id="spacer1" class="paginate_button page-item disabled">
                <button class="page-link">…</button>
            </li>
        @endif
        @if ( $paginator->hasMorePages() )
            @for ($page = max(2, $current - 1); $page < min($paginator->lastPage(), $current + 2
                + ($paginator->onFirstPage() ? 1 : 0)); $page++)
            <li class="paginate_button page-item {{$page == $current ? ' active' : ''}}">
                <button class="page-link" wire:click="gotoPage({{$page}})">{{$page}}</button>
            </li>
            @endfor

            @if($paginator->lastPage() - 2 > $current )
            <li id="spacer2" class="paginate_button page-item disabled">
                <button class="page-link">…</button>
            </li>
            @endif

            <li class="paginate_button page-item ">
                <button class="page-link"
                    wire:click="gotoPage({{$paginator->lastPage()}})">{{$paginator->lastPage()}}</button>
            </li>
            <li class="paginate_button page-item next">
                <button class="page-link" wire:click="nextPage">@lang('pagination.next')</button>
            </li>
        @else
        @for ($page = max(min(3, $current - 1), $current - 2, 2); $page < $current + 1; $page++)
        <li class="paginate_button page-item {{$page == $current ? ' active' : ''}}">
            <button class="page-link" wire:click="gotoPage({{$page}})">{{$page}}</button>
        </li>
        @endfor
            <li class="paginate_button page-item next disabled">
                <button class="page-link">@lang('pagination.next')</button>
            </li>
        @endif
    </ul>
@endif
