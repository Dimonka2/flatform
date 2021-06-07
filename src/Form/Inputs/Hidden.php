<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Hidden extends Input
{
    protected $defaultOptions = ['id', 'name'];
    protected const hasValue = false;

    protected function read(array $element)
    {
        parent::read($element);
        $this->col = false;
        if(is_array($this->value)) $this->value = json_encode($this->value);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        $options['value'] = $this->value;
        return $options;
    }
}
