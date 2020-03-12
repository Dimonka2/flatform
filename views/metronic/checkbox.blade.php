@php
    $solid  = !!$element->getAttribute('solid');
    $bold   = !!$element->getAttribute('bold');
    $tick   = !!$element->getAttribute('tick');
    $color  =   $element->getAttribute('color');
@endphp
<label class="{{$element->class
    . ($solid ? ' kt-checkbox--solid' : '')
    . ($bold ? ' kt-checkbox--bold' : '')
    . ($tick ? ' kt-checkbox--tick' : '')
    . ($color ? ' kt-checkbox--' . $color : '')
    }}" for="{{$element->id}}">
    {!! $html !!}{!! $element->label !!}
    <span></span>
</label>
