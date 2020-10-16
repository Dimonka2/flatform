<div class="tabs tab-container">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        <div class="tab-content clearfix"  id="{{ $tab->id }}" role="tabpanel"
            {!!$element->activeID == $tab->id ? "style='display:block;'" : '' !!}>
           {!! $tab->renderItems() !!}
        </div>
        @endif
    @endforeach
</div>
