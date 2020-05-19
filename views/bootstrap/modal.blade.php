@php
    $show = $element->getShow();
@endphp
    <div class="modal fade @if ($show) show d-block @endif" id="{{$element->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$element->id}}-label" aria-hidden="true">
        <div class="modal-dialog{{$element->getSize() ? ' modal-'. $element->getSize() : ''}}" role="document">
            <div class="modal-content">
                <div class="modal-header">
                @if($element->hasTitle())
                <h5 class="modal-title" id="{{$element->id}}-label">{!! $element->getTitle() !!}</h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                {!! $html !!}
                </div>
                @if($element->hasFooter())
                <div class="modal-footer">
                    {!! $element->getFooter() !!}
                </div>
                @endif
            </div>
        </div>
    </div>



