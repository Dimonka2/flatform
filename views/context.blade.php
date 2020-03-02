{{--
    This is a top level container to render everything inside context
    This container is required to make working blade instructions like "push", "stack" and "extend"
--}}
{!! $context->_internalRender() !!}
