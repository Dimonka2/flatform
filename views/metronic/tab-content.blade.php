<div class="tab-content">
    @foreach ($element->getElements() as $tab)
        @if(!$tab->getHidden())
        <div class="tab-pane @if($loop->first) active @endif" id="{{ $tab->id }}" role="tabpanel">
           {!! $tab->renderItems() !!}
        </div>
        @endif
    @endforeach
</div>
