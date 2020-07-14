<nav class="flex-sm-00-auto" aria-label="breadcrumb">
    <ol class="breadcrumb{{isset($element->class) ? ' ' . $element->class : ''}} ">
        @foreach($element->items as $breadcrumb)
        @php($hasUrl = $breadcrumb['url'] ?? false)
        <li class="breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
            @form([$breadcrumb])
        </li>
        @endforeach
        @foreach($element->nav_right ?? [] as $breadcrumb)
        <li class="{{$loop->first ? 'ml-auto ' : ''}}breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
            @form([$breadcrumb])
        </li>
        @endforeach
    </ol>
</nav>
