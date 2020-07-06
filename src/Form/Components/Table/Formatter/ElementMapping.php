<?php

namespace dimonka2\flatform\Form\Components\Table\Formatter;

class ElementMapping
{
    public const bindings = [

        // inputs
        'check'         => Check::class,
        'date'          => Date::class,
        'number'        => Number::class,
        'state'         => State::class,
    ];

    static protected function getBinding($type)
    {
        if (self::bindings[$type] ?? false ) {
            return self::bindings[$type];
        }
    }

    static public function map($item): ?BaseFormatter
    {
        // process full filter definition
        if(is_array($item)){
            $type = $item[0];
            $binding = self::getBinding($type);
            if($binding) return new $binding($item);
        } else {
            $binding = self::getBinding($item);
            if($binding) return new $binding;
        }
        return null;
    }
}
