@push($context->getJsStack())
    <script>
        function select2init(selector) {
            // console.log(selector, url);
            let el = $(selector);
            let url = el.attr('ajax-url');
            let method = el.attr('method');
            let dataFunction = el.attr('dataFunction');
            if(url) {
                $(selector).select2({
                    theme: 'bootstrap4',
                    tags: !!$(selector).attr('tags'),
                    ajax: {
                    dataType: 'json',
                    method: method ? method : 'GET',
                    url: url,
                    data: function (params) {
                        let data = {
                            q: $.trim(params.term),
                            _token: '{{csrf_token()}}'
                        };
                        if(dataFunction) data = window[dataFunction](data);
                        return data;
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

        function initSelect2()
        {
            // add select2 initialization per element
            $('.select2:not(.select2-enabled)').each(function(i, obj) {
                $(obj).addClass('select2-enabled');
                select2init(obj);
            });
        }

        $(document).ready(function () {
            initSelect2();
        })
    </script>

@endpush
