<div class="{{is_null($element->group_class) ? 'dropdown' : $element->group_class}}{{
    !is_null($element->group) ? ' btn-group' : '' }}">

    {!! $element->renderButton() !!}

    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-{{$element->direction ?? 'right'}} {{
        ($element->shadow ? ' shadow': '') .
        ($element->dropdown_class ? ' ' . $element->dropdown_class : '' ) }}">
    @if($element->count() > 0)
        <ul class="kt-nav">
            {!! $element->renderItems() !!}
        </ul>
        @endif
        @if($element->hasForm())
            {!! $element->renderDropForm() !!}
        @endif
    </div>
</div>
