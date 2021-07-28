<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" aria-label="breadcrumb">
    <div class="d-flex">
        <div class="breadcrumb">
        @foreach($element->items as $breadcrumb)
        <a class="breadcrumb-item {{isset($breadcrumb['active']) ? ' active': ''}}"
            href="{{$breadcrumb['url']}}"{!!
            isset($breadcrumb['target']) ? ' target="'. $breadcrumb['target'] . '"' : ''  !!}>
            {!! isset($breadcrumb['icon']) ? '<i class="' . $breadcrumb['icon'] . '"></i>' : '' !!}
            {!! $breadcrumb['title'] !!}
        </a>
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
        </div>
    </div>
</div>
