@php($progress = (int) (($element->position ?? 0) * 100) )
<div class="progress" {!! $element->height ? 'style="height:' . $element->height .';"' : '' !!}>
    <div class="progress-bar{{$element->striped ? ' progress-bar-striped'  : ''}}{{
            !is_null($element->color) ? ' bg-' . $element->color : '' }}{{
            $element->animated ? ' progress-bar-animated' : ''}}"
        role="progressbar" style="width: {{ $progress  }}%"
        aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"{!!
            $element->style ? ' style="' . $element->style . '"' : ""
            !!}{!!
            $element->id !== '' ? ' id="' . $element->id . '"' : ""
            !!}>
    </div>
</div>
