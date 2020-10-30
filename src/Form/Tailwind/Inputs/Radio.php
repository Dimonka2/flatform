<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Tailwind\Inputs\Input;

class Radio extends Input
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
        $options = parent::getOptions(['value', 'checked']);
        if(is_null($this->checked) && $this->name && !is_null($this->value)) {
            $checked = $this->needValue() == $this->value;
            if($checked) $options['checked'] = '';
        }
        return $options;
    }
}
