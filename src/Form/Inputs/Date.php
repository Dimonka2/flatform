<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Flatform;
use Form;

class Date extends Input
{
    public function render(IContext $context)
    {
        // add assets

        return Form::text($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
