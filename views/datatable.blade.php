<table class="table table-hover {{$element->class ?? ''}}" id="{{$element->id}}" width="100%">
</table>


@push($context->getJsStack())
    @php($jsdt = $element->js_variable ? $element->js_variable : 'dt_' . Str::camel($element->id) )
    <script type="text/javascript">
        var {{$jsdt}} = false;

        function addFilter(table){
            let row = $(table).parent().parent().parent().find('.row:eq(0)');
            let col = row.children().last();
            col.addClass('text-right');
            col.children().last().addClass('d-inline');
            let filter = {!! json_encode(Flatform::render([
                    ['div', 'class' => 'd-inline ml-2 mr-2 btn-group', [
                        ['dropdown', 'group', 'color' => 'outline-secondary', 'size' => 'sm', 'shadow',
                            'title' => [['include', 'name' => 'flatform::icons.filter',
                                'with' => ['width' => '1.5rem', 'height' => '1.5rem']]],
                            [
                                ['dd-divider'],
                        ]]

                    ]]
                ] )) !!};
            col.append(filter);
            // $(table).parent().parent().parent().find('.row .col-md-6:eq(1)').append('test');
        }

        $(document).ready(function () {
            let options = {!! $element->getTableOptions() !!};
            {!! $element->option_function ?? '' !!}
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
            // {{-- Add filter component --}}
            @if( $element->hasFilter() )
                addFilter($('#{{$element->id}}'));
            @endif

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

@if($element->hasDetails())
    @include('flatform::datatable.details-js')
@endif


