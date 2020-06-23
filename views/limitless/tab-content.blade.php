<div class="block-content block-content-full tab-content px-md-5">
    @foreach ($element as $tab)
        @if(!$tab->getHidden())
        <div class="tab-pane {{$element->activeID == $tab->id ? 'active' : '' }}"  id="{{ $tab->id }}" role="tabpanel">
           {!! $tab->renderItems() !!}
        </div>
        @endif
    @endforeach
</div>
