@if($column)
    <th class="text-nowrap text-truncate pr-0 {{$column->getClass()}}"
        wire:click.prevent='sortColumn("{{$column->getName()}}")'
        style="{{$column->getWidth() ? 'width:' . $column->getWidth() . ';': '' }} ">
        @if($column->getSort() !== false)
            <a href="#" class="d-block">
                <div class="float-right text-nowrap">
                    @if($order == 'ASC')
                        <span style='margin-right:-0.3rem;' class="text-dark">&#x1F819;</span>
                    @else
                        <span style='margin-right:-0.3rem;' class="text-muted">&#x2191;</span>
                    @endif
                    @if($order == 'DESC')
                        <span class="text-dark mr-1">&#x1F81B;</span>
                    @else
                        <span class="text-muted mr-1">&#x2193;</span>
                    @endif
                </div>
                <div class="text-slate-600 d-inline-block mr-3">
                    {!! $column->getTitle(true) !!}
                </div>
            </a>
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
