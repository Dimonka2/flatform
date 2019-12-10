<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Context;
use dimonka2\flatform\Flatform;
use Form;

class Date extends Input
{
    public function render(Context $context)
    {
        // add assets

        return Form::text($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
