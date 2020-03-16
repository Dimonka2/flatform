<div class="card">
    @if( $element->hasHeader() )
    <div class="card-header header-elements-inline">
        @if( $element->hasTitle() )
            <h6 class="card-title">
                {!! $element->icon ?? '' !!}
                {!! $element->getTitle() ?? '' !!}
            </h6>
            @if( $element->hasTools() )
            <div class="header-elements">
                {!! $element->renderTools() !!}
            </div>
            @endif
        @endif
    </div>
    @endif
    @if ($element->hasBody())
    <div class="card-body">
        {!! $element->renderBody() !!}
    </div>
    @endif
    @if ($element->hasFooter())
    <div class="card-footer">
        {!! $element->renderFooter() !!}
    </div>
    @endif
</div>
