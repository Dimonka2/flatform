<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Tailwind\Inputs\Input;

class Checkbox extends Input
{
    public $checked;
    protected $labelFirst = false;

    protected function hasValue()
    {
        return false;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['checked']);
        parent::read($element);
        $this->requireID();
    }

    protected function createCol()
    {
        $col = parent::createCol();
        $col->addClass('flex items-center');
        return $col;
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
