<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Checkbox extends Input
{
    public $label;

    protected function render()
    {
        return Form::checkbox($this->name, $this->name, $this->value,
            $this->getOptions([]));
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['label']);
        parent::read($element);
    }
}
