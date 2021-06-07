<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Checkbox extends Input
{
    public $label;
    public $checked;

    protected const hasValue = false;

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
        $checked = $this->needValue();
        if(is_null($checked)) $checked = $this->checked;
        if($checked) $options['checked'] = '';
        if(!($options['value'] ?? false)) $options['value'] = 1;
        return $options;
    }
}
