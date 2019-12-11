<div class="block-content block-content-full tab-content px-md-5" style="min-height: 303px;">
    @foreach ($element->getElements() as $tab)           
        @if(!$tab->getHidden())                   
        <div class="tab-pane @if($loop->first) active @endif" id="{{ $tab->id }}" role="tabpanel">
           {!! $tab->renderItems() !!}
        </div>
        @endif
    @endforeach
</div>