<ul class="nav nav-tabs nav-tabs-alt nav-justified" role="tablist">
    @foreach ($element->getElements() as $tab)   
        @if(!$tab->getHidden())                        
        <li class="nav-item">
            <a class="nav-link @if($loop->first) active @endif" href="#{{ $tab->id }}" data-toggle="tab">
                {{ $tab->title }}
            </a>
        </li>
        @endif
    @endforeach
</ul>