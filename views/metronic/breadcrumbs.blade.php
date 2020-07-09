<div class="kt-portlet p-0" aria-label="breadcrumb">
    <div class='kt-portlet__body p-0'>
        <nav>
        <ol class="breadcrumb m-0{{isset($element->class) ? ' ' . $element->class : ''}} ">
            @foreach($element->items as $breadcrumb)
            <li class="breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
                @form([$breadcrumb])
            </li>
            @endforeach
            @foreach($element->nav_right ?? [] as $breadcrumb)
            <li class="{{$loop->first ? 'ml-auto ' : ''}}breadcrumb-item{{isset($breadcrumb['active']) ? ' active"  aria-current="page' : ''}}">
                @form([$breadcrumb])
            </li>
            @endforeach
        </ol>
        </nav>
    </div>
</div>
