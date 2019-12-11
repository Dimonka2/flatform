<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Number extends Input
{
    protected function render()
    {
        return Form::number($this->name, $this->value,
            $this->getOptions([]));
    }
}
