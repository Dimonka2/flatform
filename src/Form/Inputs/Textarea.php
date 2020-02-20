<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use Form;

class Textarea extends Input
{
    protected function render()
    {
        return Form::textarea($this->name, $this->value,
            $this->getOptions([]));
    }
}
