<table class="table table-hover {{$element->class ?? ''}}" id="{{$element->id}}" width="100%">
</table>

@push($context->getJsStack())

    <script type="text/javascript">

        @if($element->details)
        function format_{{$element->id}}( rowData ) {
            @if($element->details_format)
                {!! $element->details_format !!}
            @endif
            @if($element->ajax_details_url)
            var div = $('<div/>')
                .addClass( 'loading' )
                .text( 'Loading...' );
            $.ajax( {
                url: '{{ $element->ajax_details_url }}',
                data: function ( d ) {
                    d._token = "{{csrf_token()}}";
                    {{$element->ajax_details_function ?? ''}}
                },
                "dataType": "json",
                "type": "{{ $element->ajax_method ?? 'GET' }}",
                success: function ( json ) {
                    div
                .html( json.html )
                .removeClass( 'loading' );
                }
            } );
            @endif
            return div;
        }

        var detailRows = [];
        function bindDetails() {
            $('#{{$element->id}} tbody tr').on( 'click', 'td.{{$element->details}}', function () {
                var dt = $('#{{$element->id}}').DataTable();
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    row.child( format_{{$element->id}}( row.data() ) ).show();

                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );
        }
        @endif


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
                    {!! $element->formatOrder() !!}
                @endif


                columnDefs: [
                    @foreach ($element->getColDefinition() as $column)
                        {targets: [ {{$loop->index + ($element->details ? 1 : 0) }} ]
                            {!! $column->formatColumnDefs() !!} },
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
                        @if($element->details)
                        {
                            className:      '{{$element->details}}',
                            orderable:      false,
                            data:           null,
                            defaultContent: ''
                        },
                        @endif
                        @foreach ($element->getColDefinition() as $column)
                            { "data": "{{ $column->as ? $column->as : $column->name }}" },
                        @endforeach
                ]

            });

            @if($element->details)
                var dt = $('#{{$element->id}}').DataTable();
                dt.on('draw', function () {
                    bindDetails();
                    $.each( detailRows, function ( i, id ) {
                        $('#'+id+' td.details-control').trigger( 'click' );
                    } );
                });
            @endif

    });
    </script>

@endpush


