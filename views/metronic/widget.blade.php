<div class="kt-portlet kt-portlet--responsive-mobile {{$element->class ?? ''}}">
    @if( $element->hasHeader() )
    <div class="kt-portlet__head{{
        $element->getAttribute('head-no-border') ? ' kt-portlet__head--noborder' : ''}}">
        @if( $element->hasTitle() )
        <div class="kt-portlet__head-label">
            @if( $element->hasIcon() )
            <span class="kt-portlet__head-icon">
                {!! $element->getIcon() !!}
            </span>
            @endif
            <h3 class="kt-portlet__head-title{{
                $element->getAttribute('title-class') ? ' ' . $element->getAttribute('title-class') : ''}}">
                {!! $element->getTitle() !!}
            </h3>
        </div>
        @endif
        @if( $element->hasTools() )
        <div class="kt-portlet__head-toolbar">
            {!! $element->renderTools() !!}
        </div>
        @endif
    </div>
    @endif
    @if ($element->hasBody())
    <div class="kt-portlet__body {{$element->body_class ?? ''}}">
        {!! $element->renderBody() !!}
    </div>
    @endif
    @if ($element->hasFooter())
    <div class="kt-portlet__foot">
        {!! $element->renderFooter() !!}
    </div>
    @endif

</div>
