<?php

namespace dimonka2\platform\Form\Inputs;

use dimonka2\platform\Form\Input;
use dimonka2\platform\Form\Context;
use Form;

class Text extends Input
{
    public function render(Context $context)
    {
        return Form::text($this->getTag(), $this->value,
            $this->getOptions(['id', 'class', 'style', 'required',
                'disabled', 'readonly', 'placeholder']));
    }
}
