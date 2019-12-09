<?php

namespace dimonka2\platform\Form\Inputs;

use dimonka2\platform\Form\Input;
use dimonka2\platform\Form\Context;
use Form;

class Radio extends Input
{
    public function render(Context $context)
    {
        return Form::radio($this->name, $this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
