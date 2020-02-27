<?php

namespace dimonka2\flatform\Helpers;

use dimonka2\flatform\Form\Contracts\IElement;

// string format could be:
    // * "Text"
    // * div span b i u|class
    // * _text $field\modifier
    // * date "format"$field\modifier
    // * money "format"$field\modifier
    // * state "format"$field
    // * bool $field|class( [true element] )( [false element] )
    // * link "route"$field|class
    // * callback "name"
    // inputs with ajax feedback:
    // * checkbox $field|class
    // * select (options)$field|class
    // * text $field|class
    // * textarea $field|class
    // * number $field|class
    // * button "action"|class
    // Parallel elements [element] / [element]
    // Subelements > ( [element] / [element] )

class ColumnFormat
{
    public static function make($format): ?IElement
    {
        return null;
    }
}
