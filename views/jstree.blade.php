{!! $html !!}
@push('js')
    <script>
        $(document).ready(function () {
            $('#{{$element->id}}').jstree({!! json_encode($element->getTree(), JSON_PRETTY_PRINT) !!});
        })
    </script>
@endpush
