@push($context->getJsStack())
    <script>
        function initDatepickers(){
            $('.datepicker:not(.datepicker-enabled)').each(function(i, obj) {
                $(obj).addClass('datepicker-enabled');
                $(obj).datepicker({
                    dateFormat: '{{ config('flatform.form.date_format_js') }}'
                });
            });
        }

        $(document).ready(function () {
            initDatepickers();
        });
    </script>
@endpush
