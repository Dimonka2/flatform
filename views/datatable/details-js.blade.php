@push($context->getJsStack())
<script>
    @php($details = $element->getDetails())
    function format_{{Str::camel($element->id)}}( rowData ) {
        @if( $details->format_function )
            {!! $details->format_function !!}
        @endif

        var div = $('<div/>').addClass( 'loading' ).text( 'Loading...' );
        var self = this;
        @if( $details->getHasAjax() )
        $.ajax( {
            url: '{{ $details->getUrl() }}',
            data: {
                '_token': "{{csrf_token()}}",
                {!! $details->getDataDefinition() ?? ''!!}
            },
            "dataType": "json",
            "method": "{{ $details->getAjaxMethod() }}",
            success: function ( json ) {
                div.html( json.html ).removeClass( 'loading' );
                @if( $details->loaded_function )
                    {!! $details->loaded_function !!}
                @endif
            },
            error: function ( error ) {
                // console.log(error);
                div.html( error.responseJSON.error ).removeClass( 'loading' ).addClass('bg-danger text-white p-2');
            }
        } );
        @endif
        return div;
    }

    var detailRows_{{Str::camel($element->id)}} = [];
    function bindDetails(dt) {
        $('#{{$element->id}} tbody tr').on( 'click', 'td.{{trim($details->class)}}', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows_{{Str::camel($element->id)}} );

            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();

                // Remove from the 'open' array
                detailRows_{{Str::camel($element->id)}}.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                row.child( format_{{Str::camel($element->id)}}( row.data() ) ).show();

                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows_{{Str::camel($element->id)}}.push( tr.attr('id') );
                }
            }
        } );
    }
</script>
@endpush
