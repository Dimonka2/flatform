<div class="col-{{$element->col ?? 6}} form-group">
    @if(isset($element->title) )
        <label for="{{$element->id}}">{!! $element->title !!}</label>
    @endif
    {!! $html !!}
</div>