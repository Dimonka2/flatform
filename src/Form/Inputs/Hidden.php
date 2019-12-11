<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Hidden extends Element
{
    public $value;
    public $name;

    protected function read(array $element)
    {
        $this->readSettings($element, ['name', 'value']);
        parent::read($element);
    }

    protected function render()
    {
        return Form::hidden($this->name, $this->value);
    }
}
