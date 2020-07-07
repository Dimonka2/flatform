<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

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
