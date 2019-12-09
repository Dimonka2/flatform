<?php

namespace dimonka2\platform\Form\Inputs;

use dimonka2\platform\Form\Input;
use dimonka2\platform\Form\Context;
use dimonka2\platform\Platform;
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
