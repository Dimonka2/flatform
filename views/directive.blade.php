@switch($element->getTag())
    @case('stack')
        @stack($element->name)
        @break
    @case('include')
        @include($element->name, $element->with)
        @break
    @case('yield')
        @yield($element->name)
@endswitch
