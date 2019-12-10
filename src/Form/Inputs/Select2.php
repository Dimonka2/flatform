<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Select2 extends Input
{
    protected $items;
    protected $state_list;

    protected function read(array $element, IContext $context)
    {
        $fields = 'items,state_list';
        $this->readSettings($element, explode(',', $fields));
        if(is_null($this->items)) $this->items = [];
        parent::read($element, $context);
    }

    protected function render(IContext $context, $aroundHTML)
    {
        return Form::select($this->name, $this->items, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
