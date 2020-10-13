<div class="tabs tab-container">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        <div class="tab-content clearfix {{$element->activeID == $tab->id ? '' : '' }}"  id="{{ $tab->id }}" role="tabpanel">
           {!! $tab->renderItems() !!}
        </div>
        @endif
    @endforeach
</div>
