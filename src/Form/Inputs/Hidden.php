<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Hidden extends Input
{
    protected function read(array $element)
    {
        parent::read($element);
        $this->col = false;
    }
}
