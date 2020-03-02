<ul class="nav {{$element->class ? $element->class . ' ': ''}}
    {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }}
    {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        @php($active = $element->activeID == $tab->id)
        <li class="nav-item">
            @if ($tab->getHref())
                <a class="nav-link {{$active ? 'active' : '' }}" href="{{ $tab->getHref() }}">
                    {!! $tab->getTitle() !!}
                </a>
            @else
                <a class="nav-link {{$active ? 'active' : '' }}" href="#{{ $tab->id }}" data-toggle="tab" aria-selected="{{$active ? 'true' : 'false'}}">
                    {!! $tab->getTitle() !!}
                </a>
            @endif
        </li>
        @endif
    @endforeach
</ul>
