@if(isset($section))
    @inject('viewHelper', 'App\Helpers\ViewHelper')
    @switch($section)
        @case('css')
            @foreach ($viewHelper->CSS as $item)
                <link href="{!! asset($item) !!}" rel="stylesheet" type="text/css">
            @endforeach
            @break
        @case('js')
            @foreach ($viewHelper->JS as $item)
                <script src="{!! asset($item) !!}"></script>
            @endforeach
            @break
    @endswitch
@elseif (isset($elements))

    @empty($datepicker_included)
        @php($datepicker_included = 0)
    @endempty
    @foreach ($elements as $element)
        @if(empty($element['exclude']) or !$element['exclude'])
            @if (isset($element['type']) )
                @switch($element['type'])
                    @case('row')
                        <div class="row {{$element['class'] ?? ''}}">
                            @isset($element['items'])
                                @form( ['elements' => $element['items']])
                            @endisset
                        </div>
                        @break
                    @case('col')
                        @php($class = (isset($element['col']) ? 'col-md-' . $element['col'] :
                            (isset($element['class']) ? ' '. $element['class'] : 'col-md-6')) )
                        <div class="{{$class}}">
                            {{$element['text'] ?? ''}}
                            @isset($element['items'])
                                @form(['elements' => $element['items']])
                            @endisset
                        </div>
                        @break
                    @case('tabs')
                        @foreach ($element['items'] as $tab)
                            @if(empty($tab['exclude']) or !$tab['exclude'])
                                @push('tab-'. $tab['name'])
                                    @form(['elements' => $tab['items']])
                                @endpush
                            @endif
                        @endforeach
                        @tabs(['elements' => $element['items']])
                        @break;
                    @case('dropdown')
                        <div class="dropdown {{$element['group-class'] ?? ''}}">
                            <button type="button" class="btn {{$element['class'] ?? ''}}"
                                id="{{$element['id'] ?? 'dd'}}" data-toggle="dropdown">
                                {!! $element['title'] ?? '' !!}
                            </button>

                            <div class="dropdown-menu {{$element['dropdown-class'] ?? 'shadow'}}">
                                @isset ($element['items'])
                                    @form(['elements' => $element['items']])
                                @endisset
                            </div>
                        </div>
                        @break
                    @case('dd-item')
                        @php($element['class'] = 'dropdown-item' . (isset($element['class']) ? ' ' . $element['class'] : ''))
                        @button($element)
                        @break
                    @case('button')
                    @case('a')
                    @case('submit')
                        @button($element)
                        @break
                    @case('checkbox')
                        @php($element['class'] = ' form-check-input' . (isset($element['class']) ? ' ' . $element['class'] : ''))
                        @button($element)
                        @break
                    @case('div')
                    @case('span')
                    @case('ul')
                    @case('li')
                        <{{$element['type']}} class="{{$element['class'] ?? ''}}" @isset($element['style']) style="{{$element['style']}}" @endisset>
                            {{$element['text'] ?? ''}}
                            @isset($element['items'])
                                @form(['elements' => $element['items']])
                            @endisset
                        </{{$element['type']}}>
                        @break;
                    @default
                        @field(['el' => $element])
                @endswitch
            @else
                @field(['el' => $element])
            @endif
        @endif
    @endforeach
@endif
