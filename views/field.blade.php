<div class="col-md-{{$el['col'] ?? 6}} form-group">
    @if(isset($el['label']) and (empty($el['type']) or $el['type'] != 'checkbox'))
        <label for="id-{{$el['name']}}">{{$el['label']}}</label>
    @endif
    @php
        $setting = [];
        $setting['class'] = (isset($el['class']) ? $el['class'] : 'form-control form-control-alt');
        if (isset($el['id'])) {
            $setting['id'] = $el['id'];
        } else {
            $setting['id'] = 'id-' . $el['name'];
        }
        if (isset($el['required'])) $setting['required'] = true;
        if (isset($el['disabled'])) $setting['disabled'] = $el['disabled'];
        if (isset($el['placeholder'])) $setting['placeholder'] = $el['placeholder'];
        if (isset($el['rows'])) $setting['rows'] = $el['rows'];
        $value = (isset($el['value']) ? $el['value'] : null);
    @endphp
    @if(empty($el['type']))
        {!! Form::text($el['name'], $value, $setting) !!}
    @else
        @switch($el['type'])


            @case('summernote')
                @php($setting['class'] = $setting['class'] . ' summernote' )

                @inject('viewHelper', 'App\Helpers\ViewHelper')
                @if(!$viewHelper->isIncluded('summernote'))
                    @include($viewHelper::summernote)
                    @php($viewHelper->include('summernote'))
                @endif
                {!! Form::textarea($el['name'], $value, $setting) !!}
                @break

        @endswitch
    @endif
</div>
