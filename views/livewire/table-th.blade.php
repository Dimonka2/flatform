@if($column)
    @php
        $sort = $column->getSort() !== false;
        $arrowUpIcon = $order == 'ASC' ? '&#x1F819;' : '&#x2191;';
        $arrowUpClass = $order == 'ASC' ? 'text-dark' : 'text-muted';
        $arrowDownIcon = $order == 'DESC' ? '&#x1F81B;' : '&#x2193;';
        $arrowDownClass = $order == 'DESC' ? 'text-dark' : 'text-muted';
        $style = ($column->getWidth() ? "width: {$column->getWidth()};" : '') .
            ($sort ? " cursor:pointer;": "");
    @endphp
    <th class="text-nowrap text-truncate pr-0 {{$column->class . ' ' . $column->titleClass}}"
        @if($sort) wire:click.prevent='sortColumn("{{$column->getName()}}")' @endif
        style="{{$style}}"
        >
        @if($sort)
            <div class="d-flex">
                <div class="text-slate-600 d-inline-block mr-3">
                    {!! $column->getTitle(true) !!}
                </div>
                <div class="text-nowrap ml-auto">
                    <span style='margin-right:-0.3rem;' class="{{$arrowUpClass}}">
                        {!!$arrowUpIcon!!}
                    </span>
                    <span class="{{$arrowDownClass}}">{!!$arrowDownIcon!!}</span>
                </div>
            </div>
        @else
            {!! $column->getTitle(true) !!}
        @endif
    </th>
@else

    {{-- Special case for select or details --}}
        <th class="text-nowrap text-truncate {{$class ?? ''}}"{!!
            ($width ?? false) ? " style=\"width:$width;\"" : ''!!}>
        {!! $title !!}
    </th>
@endif
