@push(config('flatform.form.js_stack', 'js'))
    <script src="{!! asset('assets/plugins/select2/js/select2.full.min.js') !!}"></script>
    <script>
        function select2init(selector, url) {
            // console.log(selector, url);
            $(selector).select2({
                theme: 'bootstrap4',
                ajax: {
                dataType: 'json',
                url: url,
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
                }
            });
        }

        $(document).ready(function () {
            // add select2 initialization per element
            $('.select2').each(function(i, obj) {
                var url = $(obj).attr('ajax-url');
                if (typeof url !== typeof undefined) {
                    select2init(obj, url);
                }
            });
        })
    </script>

@endpush
@push('css')
    <link href="{!! asset('assets/plugins/select2/css/select2.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/plugins/select2/css/select2-bootstrap4.css') !!}" rel="stylesheet" type="text/css">
@endpush
