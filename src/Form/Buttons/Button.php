<?php

namespace dimonka2\platform\Form\Buttons;

use dimonka2\platform\Form\Link;
use dimonka2\platform\Form\Context;
use Form;

class Button extends Link
{
    public function render(Context $context)
    {
        return Form::text($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
