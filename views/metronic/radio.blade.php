@php
    $solid  = !!$element->getAttribute('solid');
    $bold   = !!$element->getAttribute('bold');
    $tick   = !!$element->getAttribute('tick');
    $color  =   $element->getAttribute('color');
@endphp
<label class="{{$element->class
    . ($solid ? ' kt-radio--solid' : '')
    . ($bold ? ' kt-radio--bold' : '')
    . ($tick ? ' kt-radio--tick' : '')
    . ($color ? ' kt-radio--' . $color : '')
    }}" for="{{$element->id}}">
    {!! $html !!}{!! $element->label !!}
    <span></span>
</label>
