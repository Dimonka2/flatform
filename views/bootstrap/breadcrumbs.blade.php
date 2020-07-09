<nav class="flex-sm-00-auto" aria-label="breadcrumb">
    <ol class="breadcrumb{{isset($element->class) ? ' ' . $element->class : ''}} ">
        @foreach($element->items as $breadcrub)
        @php($hasUrl = $breadcrub['url'] ?? false)
        <li class="breadcrumb-item{{isset($breadcrub['active']) ? ' active"  aria-current="page' : ''}}">
            @form([$breadcrumb])
        </li>
        @endforeach
        @foreach($element->nav_right ?? [] as $breadcrub)
        <li class="{{$loop->first ? 'ml-auto ' : ''}}breadcrumb-item{{isset($breadcrub['active']) ? ' active"  aria-current="page' : ''}}">
            @form([$breadcrumb])
        </li>
        @endforeach
    </ol>
</nav>
