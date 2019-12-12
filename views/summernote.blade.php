
 @push(config('flatform.form.css_stack', 'css'))
    <link href="{!! asset('summernote/summernote.css') !!}" rel="stylesheet" />
    <link href="{!! asset('summernote/summernote-bs4.css') !!}" rel="stylesheet" />
@endpush

@push(config('flatform.form.js_stack', 'js'))
    <!-- Summernote -->
    <script src="{!! asset('summernote/summernote.min.js') !!}"></script>
    <script src="{!! asset('summernote/summernote-bs4.min.js') !!}"></script>


    <style>
        h1 div.note-editable {font-size: 14px;}
    </style>

    <script>
        function summernote_this(text, tool_bar)
        {
            if (!tool_bar) {
                tool_bar = [
                //[groupname, [button list]]
                ['tools', ['fullscreen', 'codeview']],
                ['style', ['bold', 'italic', 'underline', 'strikethrough', 'style']],
                ['font', ['fontname', 'superscript', 'subscript']],
                ['fontsize', ['fontsize', 'color'], ],
                ['more', ['clear', 'table', 'hr', 'link', 'picture']],
                ['para', ['ul', 'ol', 'paragraph']],
            ];
            }

            $(text).summernote({
            @if(\App::getLocale() == 'de')
            lang: "de-DE",
            @endif
            @if(\App::getLocale() == 'ru')
            lang: "ru-RU",
            @endif
            toolbar: tool_bar
            @isset($settings)
            {!! $settings !!}
            @endisset
            });

        }

        $(document).ready(function()
        {
            summernote_this('.summernote');
        });
    </script>

    <!-- Summernote -->
@endpush
