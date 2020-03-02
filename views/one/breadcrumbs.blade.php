<nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
        @foreach($element->items as $breadcrumb)
        <li class="breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
            <a class="link-fx" href="{{$breadcrumb['url']}}"{!!
                isset($breadcrumb['target']) ? ' target="'. $breadcrumb['target'] . '"' : ''  !!}>
                {!! isset($breadcrumb['icon']) ? '<i class="' . $breadcrumb['icon'] . '"></i>' : '' !!}
                {!! $breadcrumb['title'] !!}
            </a>
        </li>
        @endforeach
        @foreach($element->nav_right ?? [] as $breadcrumb)
        <li class="{{$loop->first ? 'ml-auto ' : ''}}breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
            <a class="link-fx" href="{{$breadcrumb['url']}}"{!!
                isset($breadcrumb['target']) ? ' target="'. $breadcrumb['target'] . '"' : ''  !!}>
                {!! isset($breadcrumb['icon']) ? '<i class="' . $breadcrumb['icon'] . '"></i>' : '' !!}
                {!! $breadcrumb['title'] !!}
            </a>
        </li>
        @endforeach
    </ol>
</nav>
