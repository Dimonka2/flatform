<?php

namespace dimonka2\flatform\Form\Bootstrap\Inputs;

use dimonka2\flatform\Form\Bootstrap\Input;

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
        if(!($options['value'] ?? false)) $options['value'] = 1;
        return $options;
    }
}
