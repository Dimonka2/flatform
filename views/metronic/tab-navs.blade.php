<ul class="nav {{$element->class ? $element->class . ' ': ''}}
    {{$element->pills ? 'nav-pills' : 'nav-tabs nav-tabs-alt' }}
    {{$element->justified ? 'nav-justified' : ''}}" role="tablist">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        @php
            $active = $element->activeID == $tab->id;
            $disabled = $tab->getAttribute('disabled');
        @endphp
        <li class="nav-item">
            @php
                $tab->addClass('nav-link');
                if($element->isTab($tab)){

                    if($active) $tab->addClass('active');
                    if($disabled) $tab->addClass('disabled');
                    if(!$tab->getHref()) {
                        $tab->setAttribute('data-toggle', 'tab')
                            ->setAttribute('aria-selected', $active ? 'true' : 'false')
                            ->setAttribute('role', 'tab')
                            ->setAttribute('aria-controls', $tab->id)
                            ->setAttribute('href', '#' . $tab->id)
                            ->setDefaultOptions(['class', 'style']); // this is needed to exclude rendering of ID attribute
                    }
                }
                echo $tab->renderElement();
            @endphp

        </li>
        @endif
    @endforeach
</ul>
