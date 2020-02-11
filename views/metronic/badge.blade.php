<span class="kt-badge {{$element->getPill() ? ' kt-badge--pill' : ''}}
    kt-badge--{{$element->getColor()}}{{$element->getInline() ? ' kt-badge--inline' : ''}}
    {{$element->getSize() ? ' kt-badge--' . $element->getSize() : ''}} {{$element->class}}">
    {!! $html !!}
</span>
