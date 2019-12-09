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
            @case('text')
                {!! Form::text($el['name'], $value, $setting) !!}
                @break

            @case('password')
                {!! Form::password($el['name'], $value, $setting) !!}
                @break

            @case('select')

                @isset($el['state-list'])
                    @php( $el['list'] = \App\Helpers\StateHelper::selectStateList($el['state-list']) )
                @endisset
                {!! Form::select($el['name'], $el['list'], $value, $setting) !!}
                @break

            @case('textarea')
                {!! Form::textarea($el['name'], $value, $setting) !!}
                @break

            @case('file')
                {!! Form::file($el['name'], $setting) !!}
                @break

            @case('radio')
                {!! Form::radio($el['name'], $el['name'], $value, $setting) !!}
                @break

            @case('number')
                {!! Form::number($el['name'], $value, $setting) !!}
                @break

            @case('date')
                @php($setting['class'] = $setting['class'] . ' datepicker' )

                @inject('viewHelper', 'App\Helpers\ViewHelper')
                @if(!$viewHelper->isIncluded('datepicker'))
                    @include($viewHelper::datapicker)
                    @php($viewHelper->include('datepicker'))
                @endif
                {!! Form::text($el['name'], $value, $setting) !!}
                @break

            @case('summernote')
                @php($setting['class'] = $setting['class'] . ' summernote' )

                @inject('viewHelper', 'App\Helpers\ViewHelper')
                @if(!$viewHelper->isIncluded('summernote'))
                    @include($viewHelper::summernote)
                    @php($viewHelper->include('summernote'))
                @endif
                {!! Form::textarea($el['name'], $value, $setting) !!}
                @break

            @case('select2')
                @php($setting['class'] = $setting['class'] . ' select2' )
                @isset($el['ajax-url'])
                    @php($setting['ajax-url'] = $el['ajax-url'] )
                @endisset
                @php($setting['style'] = isset($setting['style'])? $setting['style'] : 'width: 100%;' )

                @inject('viewHelper', 'App\Helpers\ViewHelper')
                @if(!$viewHelper->isIncluded('select2'))
                    @include($viewHelper::select2)
                    @php($viewHelper->include('select2'))
                @endif
                {!! Form::select($el['name'], (isset($el['list']) ? $el['list'] : []), $value, $setting) !!}
                @break
        @endswitch
    @endif
</div>
