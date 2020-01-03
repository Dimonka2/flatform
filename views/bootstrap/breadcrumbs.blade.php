<nav class="flex-sm-00-auto" aria-label="breadcrumb">
    <ol class="breadcrumb{{isset($element->class) ? ' ' . $element->class : ''}} ">
        @foreach($element->items as $breadcrub)
        <li class="breadcrumb-item{{isset($breadcrub['active']) ? ' active"  aria-current="page' : ''}}">
            <a class="link-fx" href="{{$breadcrub['url'] ?? '#'}}"{!!
                isset($breadcrub['target']) ? ' target="'. $breadcrub['target'] . '"' : ''  !!}>
                {!! isset($breadcrub['icon']) ? '<i class="' . $breadcrub['icon'] . '"></i>' : '' !!}
                {!! $breadcrub['title'] !!}
            </a>
        </li>
        @endforeach
        @foreach($element->nav_right ?? [] as $breadcrub)
        <li class="{{$loop->first ? 'ml-auto ' : ''}}breadcrumb-item{{isset($breadcrub['active']) ? ' active"  aria-current="page' : ''}}">
            <a class="link-fx" href="{{$breadcrub['url'] ?? '#'}}"{!!
                isset($breadcrub['target']) ? ' target="'. $breadcrub['target'] . '"' : ''  !!}>
                {!! isset($breadcrub['icon']) ? '<i class="' . $breadcrub['icon'] . '"></i>' : '' !!}
                {!! $breadcrub['title'] !!}
            </a>
        </li>
        @endforeach
    </ol>
</nav>
