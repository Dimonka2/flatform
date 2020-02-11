<ul class="nav {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }} {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        <li class="nav-item">
            @php($active = $element->activeID == $tab->id)
            <a class="nav-link {{$active ? 'active' : '' }}" href="#{{ $tab->id }}" data-toggle="tab" aria-selected="{{$active ? 'true' : 'false'}}">
                {!! $tab->getTitle() !!}
            </a>
        </li>
        @endif
    @endforeach
</ul>
