<div class="{{is_null($element->group_class) ? 'dropdown' : $element->group_class}}{{
        !is_null($element->group) ? ' btn-group' : '' }}">

    {!! $element->renderButton() !!}

    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-{{$element->direction ?? 'right'}} {{
            ($element->shadow ? ' shadow': '') .
            ($element->dropdown_class ? ' ' . $element->dropdown_class : '' ) }}">
        {!! $element->renderItems() !!}
        @if($element->hasForm())
            {!! $element->renderDropForm() !!}
        @endif

    </div>
</div>
