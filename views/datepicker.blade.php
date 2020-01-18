@push($context->getJsStack())
    <script>
        $(document).ready(function () {
            $('.datepicker').datepicker({
            dateFormat: '{{ config('flatform.form.date_format_js') }}'
            });
        });
    </script>
@endpush
