@push($context->getJsStack())
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
