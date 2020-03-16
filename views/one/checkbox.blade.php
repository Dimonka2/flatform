<div class="custom-control custom-checkbox{{
    $element->getAttribute('inline') ? ' custom-control-inline"' : ''}}">
    {!! $html !!}
    <label class="custom-control-label" for="{{$element->id}}">{!! $element->label !!}</label>
</div>
