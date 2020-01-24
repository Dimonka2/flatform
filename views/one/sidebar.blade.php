@php($main = $element->getTag() == 'menu')
@if ($main)
    <ul class="nav-main">
@endif
    @foreach ($element as $item)
        @if (!$item->getHidden() )
            @if (count($item) > 0)
                {{-- This is a menu with subitems --}}
                <li class="nav-main-item{{ $item->getOpen() ? ' open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="true" href="#">
                        @if ($item->icon)
                            <i class="nav-main-link-icon {{$item->icon}}"></i>
                        @endif
                        <span class="nav-main-link-name">{!!$item->title!!}</span>
                    </a>
                    <ul class="nav-main-submenu">
                        @include('flatform::one.sidebar', ['element' => $item])
                    </ul>
                </li>
            @else
                <li class="nav-main-item">
                    <a class="nav-main-link{{ ($item->active ? ' active' : '') }}" href="{{$item->getHref()}}">
                        @if($item->icon)
                            <i class="nav-main-link-icon {{$item->icon}}"></i>
                        @endif
                        <span class="nav-main-link-name">{!!$item->title!!}</span>
                        @if($item->getBadge())
                            <span class="badge badge-pill badge-{{$item->getBadgeColor() ?? 'danger'}}">
                                {!! $item->getBadge() !!}
                            </span>
                        @endif
                    </a>
                </li>
            @endif
        @endif
    @endforeach
@if($main)
    </ul>
@endif
