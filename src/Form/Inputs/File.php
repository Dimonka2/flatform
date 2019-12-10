<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Context;
use Form;

class File extends Input
{
    public function render(Context $context)
    {
        return Form::file($this->name, $this->getOptions(['id', 'class', 'style']));
    }
}
