<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Checkbox extends Input
{
    public function render(IContext $context)
    {
        return Form::checkbox($this->name, $this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
