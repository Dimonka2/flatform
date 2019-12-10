<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Context;
use Form;

class Radio extends Input
{
    public function render(Context $context)
    {
        return Form::radio($this->name, $this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
