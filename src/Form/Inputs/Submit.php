<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Submit extends Input
{
    public function render(IContext $context)
    {
        return Form::submit($this->name, $this->getOptions(['id', 'class', 'style']));
    }
}
