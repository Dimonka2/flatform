@php($main = $element->getTag() == 'menu')
@if ($main)
<div class="card card-sidebar-mobile">
    <ul class="nav nav-sidebar" data-nav-type="accordion">
@else
    <ul class="nav nav-group-sub" data-submenu-title="{{$element->title}}">
@endif
    @if ($main || count($item) > 0)
        @foreach ($element as $item)
        @if (!$item->getHidden() )
            @if (count($item) > 0)
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        @if($item->icon)
                            <i class="icon {{$item->icon}}"></i>
                        @endif
                        <span>{!!$item->title!!}</span>
                        @if($item->getBadge())
                        {!! $item->renderBadge() !!}
                        {{--
                        <span class="badge bg-blue-400 align-self-center ml-auto">2.2</span>
                        --}}
                        @endif
                    </a>
                    @include('flatform::limitless.menu', ['element' => $item])
                </li>
            @else
                @if($item->getHref())
                <li class="nav-item">
                    <a href="{{$item->getHref()}}" class="nav-link">
                        @if($item->icon)
                        <i class="{{$item->icon}}"></i>
                        @endif
                        <span>{!!$item->title!!}</span>
                        @if($item->getBadge())
                            {!! $item->renderBadge() !!}
                            {{--
                            <span class="badge bg-blue-400 align-self-center ml-auto">2.2</span>
                            --}}
                        @endif
                    </a>
                </li>
                @else
                <li class="nav-item-header">
                    <div class="font-size-xs line-height-xs">{!!$item->title!!}</div>
                    @if($item->icon)
                    <i class="{{$item->icon}}" title="{{$item->title}}"></i>
                    @endif
                    @if($item->getBadge())
                        {!! $item->renderBadge() !!}
                        {{--
                        <span class="badge bg-blue-400 align-self-center ml-auto">2.2</span>
                        --}}
                    @endif
                </li>
                @endif
            @endif
        @endif
        @endforeach
    @endif
    </ul>
@if ($main)
</div>
@endif

