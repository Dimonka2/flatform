@if($column)
    <th class="text-nowrap text-truncate {{$column->getClass()}}"
        wire:click.prevent='sortColumn("{{$column->getName()}}")'
        style="{{$column->getWidth() ? 'width: ' . $column->getWidth() : '' }} ">

        @php
        switch ($order) {
            case 'ASC':
                $sortingClass = 'fa fa-sort-up text-danger';
                break;
            case 'DESC':
                $sortingClass = 'fa fa-sort-down text-danger';
                break;

            default:
                $sortingClass = 'fa fa-sort text-slate-300';
                break;
        }
        @endphp

        @if($column->getSort())
            <a href="#" class="d-block">
                <div class="float-right d-block ml-2">
                    <i class="text-nowrap {{$sortingClass}}"></i>
                </div>
                <div class="text-slate-600 d-inline-block mr-3">
                    {!! $column->getTitle() !!}
                </div>
            </a>
        @else
            {{$column->getTitle()}}
        @endif
    </th>
@else
    {{-- Special case for select or details --}}
        <th class="text-nowrap text-truncate {{$class ?? ''}}"{!!
            ($width ?? false) ? " style=\"width:$width;\"" : ''!!}>
        {!! $title !!}
    </th>
@endif
