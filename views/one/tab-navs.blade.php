<ul class="nav {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }} {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        <li class="nav-item">
            <a class="nav-link @if($loop->first) active @endif" href="#{{ $tab->id }}" data-toggle="tab">
                {!! $tab->getTitle() !!}
            </a>
        </li>
        @endif
    @endforeach
</ul>
