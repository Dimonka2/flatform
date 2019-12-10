<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Context;
use Form;

class Password extends Input
{
    public function render(Context $context)
    {
        return Form::password($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
