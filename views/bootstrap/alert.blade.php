<div class="alert d-flex flex-row alert-{{$element->color}}{{ $element->close ? ' fade show' : ''}}{{
      isset($element->class) ? ' ' . $element->class : ''  }}" role="alert">
    @isset($element->icon)
        <div class="alert-icon"><i class="{{$element->icon}}"></i></div>
    @endisset
    @if($element->hasTitle())
        <h4 class="alert-heading">{!! $element->getTitle() !!}</h4>
    @endif
    @if($element->hasText())
        <div class="alert-text">{!! $element->getText() !!}</div>
    @endif
    @isset($element->close)
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endisset
    {!! $html !!}
</div>
