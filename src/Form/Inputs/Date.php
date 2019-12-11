<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Flatform;
use Form;

class Date extends Input
{
    protected function render()
    {
        // add assets

        return Form::text($this->name, $this->value,
            $this->getOptions([]));
    }
}
