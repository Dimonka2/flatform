{!! $html !!}
@push('js')
    <script>
        $(document).ready(function () {
            let tree = {!! json_encode($element->getTree(), JSON_PRETTY_PRINT) !!};
            @php($ajax = $element->getAjax())
            @if($ajax->hasDataFunction() )
                tree.core.data.data = {!! $element->getAjax()->getDataFunction() !!};
            @endif
            $('#{{$element->id}}').jstree(tree);
        })
    </script>
@endpush
