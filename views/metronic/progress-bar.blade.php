@php($progress = (int) (($element->progress ?? 0) * 100) )
<div class="progress">
    <div class="progress-bar{{if($element->striped) ? ' progress-bar-striped'  :''}}{{
            if(!is_null($element->color) ) ? ' kt-bg-' . $element->color : '' }}{{
            if($element->animated) ? ' progress-bar-animated' : ''}}{{
            if($element->size) ? ' progress-' , $element->size : ''}}"
        role="progressbar" style="width: {{ $progress  }}%"
        aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"{!!
            if($element->style !== '') ' style="' . $elemenet->style . '"'
            !!}{!!
            if($element->id !== '') ' id="' . $elemenet->id . '"'
            !!}>{!! $html !!}</div>
</div>
