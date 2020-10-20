<div class="tabs tabs-bordered clearfix ui-tabs ui-corner-all ui-widget ui-widget-content">

    <ul class="tab-nav ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header" role="tablist">
        @foreach ($element as $tab)


        @if(!$tab->getHidden())
        @php
            $active = $element->activeID == $tab->id;
        @endphp
        <li class="nav-item {{$active ? 'ui-tab ui-tabs-active ui-state-active' : ''}}" tabindex="{{$active ? 0 : -1 }}">
            @php
                $tab->addClass('nav-link');
                if($element->isTab($tab)){

                    if($active) $tab->addClass('active');

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

</div>
