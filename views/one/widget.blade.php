<div class="block block-bordered">
    @if( $element->hasHeader() )			
    <div class="block-header">
        @if( $element->hasTitle() )
            <div class="block-title">	
                <h2 class="h3 w-75 text-gray-900 mb-0">
                    {!! $element->icon ?? '' !!}
                    {!! $element->title ?? '' !!}
                </h2>
            </div>
            @if( $element->hasTools() )
                <div class="block-options">
                    {!! $element->renderTools() !!}
                </div>
            @endif
        @endif
    </div>
    @endif
    @if ($element->hasBody())
    <div class="block-content {{ !$element->hasFooter() ? 'block-content-full' : '' }}">
        {!! $element->renderBody() !!}
    </div>
    @endif
    @if ($element->hasFooter())
    <div class="block-content block-content-full bg-body-light">
        {!! $element->renderFooter() !!}
    </div>
    @endif
</div>