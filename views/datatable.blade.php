<table class="table table-hover {{$element->class ?? ''}}" id="{{$element->id}}" width="100%">
</table>


@push($context->getJsStack())
    @php($jsdt = $element->js_variable ? $element->js_variable : 'dt_' . Str::camel($element->id) )
    <script type="text/javascript">
        var {{$jsdt}} = false;

        @if( $element->hasFilter() )
        var filterClass = "{{\dimonka2\flatform\Form\Components\Datatable\DTFilter::filterClass}}";
        function addFilter(){
            if(!$('#{{$element->id}}').hasClass(filterClass+'_enabled')) {
                $('#{{$element->id}}').addClass(filterClass+'_enabled');
                // console.log($('.table-responsive').html());
                let row = $('#{{$element->id}}').parent().parent().parent().find('.row:eq(0)');
                // console.log(row);
                let col = row.children().last();
                col.addClass('text-right');
                col.children().last().addClass('d-inline');
                let filter = {!! $element->filterDropdown() !!};
                col.append(filter);
                if (typeof initBSPickers !== "undefined") initBSPickers();
                if (typeof initSelect2 !== "undefined") initSelect2();

                col.find('.' + filterClass + ':not(label)').change(function(){
                    {{$jsdt}}.ajax.reload();
                });
            }
            // $(table).parent().parent().parent().find('.row .col-md-6:eq(1)').append('test');
        }
        @endif

        $(document).ready(function () {
            let options = {!! $element->getTableOptions() !!};
            {!! $element->option_function ?? '' !!}
            options.ajax.data = function ( d ) {
                d._token = "{{csrf_token()}}";
                @if( $element->hasFilter() )
                    let form = $('#{{$element->id}}_filter-form');
                    let data = $(form).serializeArray().filter(function(val) {return val.name != '_token'});
                    data = JSON.stringify( data);
                    d.filter = data;
                @endif
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

            @if( $element->hasFilter() )
                options.initComplete = function(settings, json) {
                    addFilter();
                }
            @endif

            {{$jsdt}} = $('#{{$element->id}}').DataTable(options);
            // {{-- Add filter component --}}

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
                            $('#'+id+' td.{{trim($element->getDetails()->class)}}').trigger( 'click' );
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


