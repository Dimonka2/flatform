@push(config('flatform.form.js_stack', 'js'))
    <script>
        $(document).ready(function () {
            $('.datepicker').datepicker({
            dateFormat: '{{ config('app.date_format_js') }}'
            });
        });
    </script>
@endpush
