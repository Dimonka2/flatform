<ul class="nav {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }} {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        @php
            $active = $element->activeID == $tab->id;
            $disabled = $tab->getAttribute('disabled');
            $class = 'nav-link' . ($active ? ' active' : '') .
                    ($disabled ? ' disabled' : '');
        @endphp
        <li class="nav-item">
            @if ($tab->getHref())
                <a class="{{$class}}" href="{{ $tab->getHref() }}">
                    {!! $tab->getTitle() !!}
                </a>
            @else
                <a class="{{$class}}" href="#{{ $tab->id }}" data-toggle="tab" aria-selected="{{$active ? 'true' : 'false'}}">
                    {!! $tab->getTitle() !!}
                </a>
            @endif
        </li>
        @endif
    @endforeach
</ul>
