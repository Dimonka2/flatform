<table class="table table-hover {{$class ?? ''}}" id="{{$id}}" width="100%">
</table>

@push(config('flatform.form.css_stack', 'css'))
    <link href="{!! asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') !!}" rel="stylesheet" type="text/css">
@endpush

@push(config('flatform.form.js_stack', 'js'))
    <script src="{!! asset('assets/plugins/datatable/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! asset('assets/plugins/datatable/dataTables.bootstrap4.min.js') !!}"></script>

    <script type="text/javascript">
        var table_{{$id}} = false;
        $(document).ready(function () {
            table_{{$id}} = $('#{{$id}}').DataTable({
                "processing": true,
                "serverSide": true,
                "language": {
                    "url": "{{ asset('datatable/'. \App::getLocale() . '.json' ) }}"
                },
                @isset($options) {!!$options!!} @endisset
                @isset($order) {!! $order !!} @endisset
                columnDefs: [
                    @foreach ($columns as $column)
                        {targets: [ {{$loop->index}} ]
                        @isset($column['title'])  , title: "{{$column['title']}}" @endisset
                        @isset($column['hide']) , "visible": false @endisset
                        @if(isset($column['sort']) and !$column['sort']) , "orderable": false @endisset
                        @isset($column['defs']) {!! $column['defs'] !!} @endisset },

                    @endforeach
                ],
                "ajax":{
                        "url": "{{ $ajax_url }}",
                        "dataType": "{{ $ajax_dataType ?? 'json' }}",
                        "type": "{{ $ajax_type ?? 'GET' }}",
                        "data": function ( d ) {
                            d._token = "{{csrf_token()}}";
                            {{$ajax_data_function ?? ''}}
                        }
                },
                "columns": [
                        @foreach ($columns as $column)
                            { "data": "{{ (isset($column['as']) ? $column['as'] : $column['name'])}}" },
                        @endforeach
                ]

            });
        });
    </script>

@endpush



