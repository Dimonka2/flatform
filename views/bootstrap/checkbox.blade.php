@php
    $inline   = !!$element->getAttribute('inline');
@endphp
<div class="form-check{{$inline ? ' form-check-inline' : ''}}">
    {!! $html !!}
    <label class="form-check-label" for="{{$element->id}}">{!! $element->label !!}</label>
</div>
