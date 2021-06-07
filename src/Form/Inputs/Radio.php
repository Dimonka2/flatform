<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Radio extends Input
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
        $options = parent::getOptions(['value', 'checked']);
        if(is_null($this->checked) && $this->name && !is_null($this->value)) {
            $checked = $this->needValue() == $this->value;
            if($checked) $options['checked'] = '';
        }
        return $options;
    }
}
