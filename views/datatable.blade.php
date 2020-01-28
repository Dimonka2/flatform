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
            @if(config('flatform.assets.datatable_path', '') != '')
                "language": {
                    "url": "{{ asset(config('flatform.assets.datatable_path'). \App::getLocale() . '.json' ) }}"
                },
            @endif
                {!! $element->options ?? '' !!}
                @if($element->order)
                    {!! $element->formatOrder() !!} ],
                @endif


                columnDefs: [
                    @foreach ($element->getColDefinition() as $column)
                        {targets: [ {{$loop->index}} ] {!! $column->formatColumnDefs() !!} },
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
                        @foreach ($element->getColDefinition() as $column)
                            { "data": "{{ $column->as ? $column->as : $column->name }}" },
                        @endforeach
                ]

            });
        });
    </script>

@endpush


