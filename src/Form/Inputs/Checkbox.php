<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Checkbox extends Input
{
    public $label;
    public $checked;

    protected function hasValue()
    {
        return false;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['label', 'checked']);
        parent::read($element);
        $this->requireID();
        $this->col = false;
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions(['value']);
        $checked = $this->checked;
        if(is_null($checked) && $this->name) $checked = $this->needValue();
        if($checked) $options['checked'] = '';
        return $options;
    }
}
