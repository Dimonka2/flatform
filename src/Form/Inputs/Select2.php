<?php

namespace dimonka2\platform\Form\Inputs;

use dimonka2\platform\Form\Input;
use dimonka2\platform\Form\Context;
use Form;

class Select2 extends Input
{
    protected $items;
    protected $state_list;

    protected function read(array $element, Context $context)
    {
        $fields = 'items,state_list';
        $this->readSettings($element, explode(',', $fields));
        if(is_null($this->items)) $this->items = [];
        parent::read($element, $context);
    }

    public function render(Context $context)
    {
        return Form::select($this->name, $this->items, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
