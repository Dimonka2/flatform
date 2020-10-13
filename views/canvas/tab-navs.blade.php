<div class="tabs tabs-bordered clearfix ui-tabs ui-corner-all ui-widget ui-widget-content">

    <ul class="tab-nav ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header" role="tablist">
        @foreach ($element as $tab)
            @if(!$tab->getHidden())
            @php
                $active = $element->activeID == $tab->id;
                $disabled = $tab->getAttribute('disabled');
                $class = 'nav-link' . ($active ? ' ' : '') .
                        ($disabled ? ' disabled' : '');
            @endphp
            <li class="nav-item">
                @if ($tab->getHref())
                    <a class="{{$class}}" href="{{ $tab->getHref() }}" role="presentation">
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

</div>
