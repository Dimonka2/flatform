<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Text extends Input
{
    public function render(IContext $context)
    {
        return Form::text($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
