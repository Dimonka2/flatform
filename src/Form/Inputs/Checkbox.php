<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Checkbox extends Input
{
    public $label;
    public $checked;
    public $inline;


    protected function read(array $element)
    {
        $this->readSettings($element, ['label', 'checked', 'inline']);
        parent::read($element);
        $this->col = false;
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if($this->checked || $this->needValue()) $options['checked'] = '';

        return $options;
    }
}
