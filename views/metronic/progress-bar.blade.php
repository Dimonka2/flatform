@php($progress = (int) (($element->progress ?? 0) * 100) )
<div class="progress">
    <div class="progress-bar{{$element->striped ? ' progress-bar-striped'  : ''}}{{
            !is_null($element->color) ? ' kt-bg-' . $element->color : '' }}{{
            $element->animated ? ' progress-bar-animated' : ''}}{{
            $element->size ? ' progress-' . $element->size : ''}}"
        role="progressbar" style="width: {{ $progress  }}%"
        aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"{!!
            $element->style !== '' ? ' style="' . $elemenet->style . '"' : ""
            !!}{!!
            $element->id !== '' ? ' id="' . $elemenet->id . '"' : ""
            !!}>{!! $html !!}
    </div>
</div>
