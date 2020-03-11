@push($context->getJsStack())
    <script>
        function initBSPickers() {
            $('.bselect:not(.bselect-enabled)').each(function(i, obj) {
                $(obj).addClass('bselect-enabled');
                let options = {};
                let attr = $(obj).attr('placeholder');
                if (typeof attr !== typeof undefined && attr !== false) {
                    options.title = attr;
                }
                $(obj).selectpicker(options);
            });

        }

        $(document).ready(function () {
            initBSPickers();
        });
    </script>
@endpush
