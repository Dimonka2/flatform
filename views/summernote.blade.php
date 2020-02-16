@push($context->getJsStack())
    <!-- Summernote -->
    <style>
        h1 div.note-editable {font-size: 14px;}
    </style>

    <script>
        function summernoteInit(selector)
        {
            let tool_bar = [
                    //[groupname, [button list]]
                    ['tools', ['fullscreen', 'codeview']],
                    ['style', ['bold', 'italic', 'underline', 'strikethrough', 'style']],
                    ['font', ['fontname', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize', 'color'], ],
                    ['more', ['clear', 'table', 'hr', 'link', 'picture']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ];

            let options = {
                toolbar: tool_bar,
                @if(\App::getLocale() == 'de')
                lang: "de-DE",
                @endif
                @if(\App::getLocale() == 'ru')
                lang: "ru-RU",
                @endif
            };
            let onInit = $(selector).attr('onInit');
            if(onInit && typeof window[onInit] === 'function') window[onInit](options);
            $(selector).summernote(options);
        }

        function initSummernotes() {
            $('.summernote:not(.summernote-enabled)').each(function(i, obj) {
                $(obj).addClass('summernote-enabled');
                summernoteInit(obj);
            });
        }

        $(document).ready(function()
        {
            initSummernotes();
        });
    </script>

    <!-- Summernote -->
@endpush
