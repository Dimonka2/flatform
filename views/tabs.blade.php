@if (isset($elements) )
<div class="js-wizard-simple block" >
    <ul class="nav nav-tabs nav-tabs-alt nav-justified" role="tablist">
        @foreach ($elements as $tab)   
            @if(empty($tab['exclude']) or !$tab['exclude'])                        
            <li class="nav-item">
                <a class="nav-link @if($loop->first) active @endif" href="#{{ $tab['name'] }}" data-toggle="tab">
                    {{ $tab['title'] }}
                </a>
            </li>
            @endif
        @endforeach
    </ul>

    <div class="block-content block-content-full tab-content px-md-5" style="min-height: {{$tab['min-height'] ?? '303' }}px;">
        @foreach ($elements as $tab)           
            @if(empty($tab['exclude']) or !$tab['exclude'])       
            <div class="tab-pane @if($loop->first) active @endif" id="{{ $tab['name'] }}" role="tabpanel">
                @stack('tab-'. $tab['name'])
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif