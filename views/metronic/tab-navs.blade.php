<ul class="nav {{$element->class ? $element->class . ' ': ''}}
    {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }}
    {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        @php
            $active = $element->activeID == $tab->id;
        @endphp
        <li class="nav-item">
            @php
                $tab->addClass('nav-link');
                if($active) $tab->addClass('active');
                if(!$tab->getHref()) {
                    $tab->setAttribute('data-toggle', 'tab')
                        ->setAttribute('aria-selected', $active ? 'true' : 'false')
                        ->setAttribute('href', '#' . $tab->id);
                }
                echo $tab->renderElement();
            @endphp

        </li>
        @endif
    @endforeach
</ul>
