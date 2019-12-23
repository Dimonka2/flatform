<table class="table table-hover {{$element->class ?? ''}}" id="{{$element->id}}" width="100%">
</table>

@push($context->getJsStack())

    <script type="text/javascript">

        @isset($element->js_variable)
        var {{$element->js_variable}} = false;
        @endisset

        $(document).ready(function () {
            @isset($element->js_variable)
                {{$element->js_variable}} =
            @endisset $('#{{$element->id}}').DataTable({
                "processing": true,
                "serverSide": true,
            @if(config('flatform.assets.datatable_lang_path', '') != '')
                "language": {
                    "url": "{{ asset(config('flatform.assets.datatable_lang_path'). \App::getLocale() . '.json' ) }}"
                },
            @endif
                {!! $element->options ?? '' !!}
                {!! $element->order ?? '' !!}

                columnDefs: [
                    @foreach ($element->columns as $column)
                        {targets: [ {{$loop->index}} ]
                        @isset($column['title'])  , title: "{{$column['title']}}" @endisset
                        @isset($column['hide']) , "visible": false @endisset
                        @if(isset($column['sort']) and !$column['sort']) , "orderable": false @endisset
                        @if(isset($column['sortDesc']) and $column['sortDesc']) , "orderSequence": ["desc", "asc"] @endisset
                        @isset($column['defs']) {!! $column['defs'] !!} @endisset },

                    @endforeach
                ],
                "ajax":{
                        "url": "{{ $element->ajax_url }}",
                        "dataType": "{{ $element->ajax_dataType ?? 'json' }}",
                        "type": "{{ $element->ajax_method ?? 'GET' }}",
                        "data": function ( d ) {
                            d._token = "{{csrf_token()}}";
                            {{$element->ajax_data_function ?? ''}}
                        }
                },
                "columns": [
                        @foreach ($element->columns as $column)
                            { "data": "{{ (isset($column['as']) ? $column['as'] : $column['name'])}}" },
                        @endforeach
                ]

            });
        });
    </script>

@endpush


@if($addAssets)
    @push($context->getCssStack())
        <link href="{!! asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') !!}" rel="stylesheet" type="text/css">
    @endpush

    @push($context->getJsStack())
        <script src="{!! asset('assets/plugins/datatable/jquery.dataTables.min.js') !!}"></script>
        <script src="{!! asset('assets/plugins/datatable/dataTables.bootstrap4.min.js') !!}"></script>
    @endpush
@endif

