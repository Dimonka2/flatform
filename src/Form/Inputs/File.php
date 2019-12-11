<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class File extends Input
{
    protected function render()
    {
        return Form::file($this->name, $this->getOptions([]));
    }
}
