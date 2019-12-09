<?php

namespace dimonka2\platform\Form\Inputs;

use dimonka2\platform\Form\Input;
use dimonka2\platform\Form\Context;
use Form;

class File extends Input
{
    public function render(Context $context)
    {
        return Form::select($this->name, $this->getOptions(['id', 'class', 'style']));
    }
}
