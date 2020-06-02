@php
    $inline   = !!$element->getAttribute('inline');
@endphp
<div class="form-check{{$inline ? ' form-check-inline' : ''}}">
    {!! $html !!}
    <label class="form-check-label ml-2" for="{{$element->id}}">{!! $element->label !!}</label>
    @isset($element->error)
        <div class="invalid-feedback">{!! $element->error !!}</div>
    @endisset
    @isset($element->help)
        <span class="form-text text-muted">{!! $element->help !!}</span>
    @endisset
</div>
