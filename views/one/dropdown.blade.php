<div class="{{is_null($element->group_class) ? 'dropdown' : $element->group_class}}{{
        !is_null($element->group) ? ' btn-group' : '' }}">
    <button type="button"
        class="{{!is_null($element->class) ? $element->class : 'btn btn-primary'}}{{ $element->toggle ? ' dropdown-toggle': '' }}"
        data-toggle="dropdown" aria-expanded="false">
        {!! $element->getTitle() !!}
    </button>

    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-{{$element->direction ?? 'right'}} {{ $element->shadow ? ' shadow': '' }}">
        {!! $html !!}
    </div>
</div>
