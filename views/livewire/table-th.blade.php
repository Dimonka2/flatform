@if($column)
    <th class="text-nowrap text-truncate pr-0 {{$column->getClass()}}"
        wire:click.prevent='sortColumn("{{$column->getName()}}")'
        style="{{$column->getWidth() ? 'width: ' . $column->getWidth() : '' }} ">

        @if($column->getSort())
            <a href="#" class="d-block">
                <div class="float-right text-nowrap">
                    <span style='margin-right:-0.3rem;' class="{{
                        $order == 'ASC' ? 'text-dark' : 'text-muted'}}">{!!
                        $order == 'ASC' ? '&#x1F819;' : '&#x2191;'  !!}</span>
                    <span class="{{
                        $order == 'DESC' ? 'text-dark' : 'text-muted'}}">{!!
                        $order == 'DESC' ? '&#x1F81B;' : '&#x2193;'  !!}</span>
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
