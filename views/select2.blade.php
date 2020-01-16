@push($context->getJsStack())
    <script>
        function select2init(selector) {
            // console.log(selector, url);
            let url = $(selector).attr('ajax-url');
            if(url) {
                $(selector).select2({
                    theme: 'bootstrap4',
                    tags: !!$(selector).attr('tags'),
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
            } else {
                $(selector).select2({
                    theme: 'bootstrap4',
                    tags: !!$(selector).attr('tags')
                });
            }
        }

        $(document).ready(function () {
            // add select2 initialization per element
            $('.select2:not(.select2-enabled)').each(function(i, obj) {
                $(obj).addClass('select2-enabled');
                select2init(obj);                
            });
        })
    </script>

@endpush
