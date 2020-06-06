@php
    $name = $filter->getName();
    $title = $filter->getTitle();
    $value = $value ? $value : $filter->getValue();
@endphp
@switch( $filter->getType() )
    @case('checkbox')
        @php
            $checkbox = array_merge($filter->getOptions([]),
                ['checkbox', 'label' => $title,
                    'name' => $name,
                    'wire:model' => 'filtered.' . $filter->getName(), ]);
            if($value) $checkbox['checked'] = $value;
        @endphp
        @form([
            ['col', 'md' => 12, 'class' => 'py-2', [
                $checkbox,
            ]]
        ])
        @break
    @case('select')
        @php
            $select = array_merge(
                ['select', 'label' => $title,
                    'selected' => $value,
                    'wire:model' => 'filtered.' . $name,
                    'col' => 12, 'list' => $filter->getList()
                ],
                $filter->getOptions([])
            );
            if($value) $select['selected'] = $value;

        @endphp
        @form([$select])
        @break
    @default
@endswitch
