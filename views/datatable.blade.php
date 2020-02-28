<table class="table table-hover {{$element->class ?? ''}}" id="{{$element->id}}" width="100%">
</table>

@push($context->getJsStack())
    @php($jsdt = $element->js_variable ? $element->js_variable : 'dt_' . Str::camel($element->id) )
    <script type="text/javascript">
        var {{$jsdt}};
        @if($element->hasDetails())
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
        @endif


        @isset($element->js_variable)
        var {{$element->js_variable}} = false;
        @endisset

        $(document).ready(function () {
            let options = {!! $element->getTableOptions() !!};
            options.ajax.data = function ( d ) {
                d._token = "{{csrf_token()}}";
                {!! $element->ajax_data_function ?? '' !!}
            }
            @if($element->hasSelect())
                @php($select = $element->getSelect())
                options.columns[0].render = function (e, t, a, n) {
                    let cb = {!! $select->getCheckbox() !!};
                    cb = cb.replace(/_placeholder/g, '{{$jsdt}}' + a.DT_RowId, );
                    // console.log(a);
                    return cb;
                }
                options.headerCallback = function (e, t, a, n, s) {
                    e.getElementsByTagName('th') [0].innerHTML = {!! $select->getCheckbox(true) !!};
                }
            @endif

            var {{$jsdt}} = $('#{{$element->id}}').DataTable(options);
            @if($element->hasSelect())
                {{$jsdt}}.on('change', 'label.group-select input', function () {
                    var t = $(this).closest('table').find('td:first-child .{{$select->class}} input'),
                    a = $(this).is(':checked');
                    $(t).each(function () {
                        a ? ($(this).prop('checked', !0), {{$jsdt}}.rows($(this).closest('tr')).select())  : ($(this).prop('checked', !1), {{$jsdt}}.rows($(this).closest('tr')).deselect())
                    });
                });
                {{$jsdt}}.on( 'select', function ( e, dt, type, indexes ) {
                    if ( type === 'row' ) {
                        $.each(indexes, function (i, ii) {
                            let row = {{$jsdt}}.row( ii).node();
                            $(row).find('td:first-child input').prop('checked', !0);
                        });

                    }
                } );
            @endif
            @if($element->hasDetails() || $element->hasSelect() )
                var dt = {{$jsdt}};
                dt.on('draw', function () {
                    @if($element->hasDetails())
                        bindDetails({{$jsdt}});
                        $.each( detailRows_{{Str::camel($element->id)}}, function ( i, id ) {
                            $('#'+id+' td.{{trim($details->class)}}').trigger( 'click' );
                        } );
                    @endif

                });
            @endif

    });
    </script>

@endpush


