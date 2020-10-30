<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Bootstrap\Input;

class Hidden extends Input
{
    protected $defaultOptions = ['id', 'name'];
    protected function read(array $element)
    {
        parent::read($element);
        $this->col = false;
        if(is_array($this->value)) $this->value = json_encode($this->value);
    }
}
