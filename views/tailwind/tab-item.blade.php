@php
    $tabs = $element->getParent();
    $liClass = "mr-1";
    $active = $element->id == $tabs->activeID;
    if($tabs->pills) {
    } else {
        $element->addClass('py-2 px-4');
        if($active) {
            $element->addClass('border-l border-t border-r rounded-t');
            $liClass .= ' -mb-px bg-white';
        }
    }

    
@endphp
<li class="{{$liClass}}">
    {!! $element->render() !!}
</li>