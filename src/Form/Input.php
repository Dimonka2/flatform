<?php

namespace dimonka2\platform\Form;

use Element;

class Input extends Element
{
    protected $name;
    protected $required;
    protected $disabled;
    protected $readonly;
    protected $placeholder;
    protected $label;
    protected $value;

    protected function read(array $element, Context $context)
    {
        if(isset($element['items'])) {
            $this->readItems($element['items'], $context);
            unset($element['items']);
            parent::read($element, $context);
        }
    }
}
