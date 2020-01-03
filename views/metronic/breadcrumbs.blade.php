<div class="kt-portlet p-0" aria-label="breadcrumb">
    <div class='kt-portlet__body p-0'>
        <nav>
        <ol class="breadcrumb bg-white m-0{{isset($element->class) ? ' ' . $element->class : ''}} ">
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
    </div>
</div>
