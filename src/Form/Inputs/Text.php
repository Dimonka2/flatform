<?php

namespace dimonka2\platform\Form\Inputs;
use Form;

class Text
{
    protected function render(Context $context)
    {
        return Form::text($this->getTag(), $this->value,
            $this->getOptions(['id', 'class', 'style', 'required',
                'disabled', 'readonly', 'placeholder']));
    }
}
