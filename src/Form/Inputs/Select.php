<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Select extends Input
{
    protected $state_list;
    protected $list;

    protected function read(array $element)
    {
        $fields = 'state_list,list';
        $this->readSettings($element, explode(',', $fields));
        parent::read($element);
        if(is_null($this->list)) $this->list = [];
    }

    protected function render()
    {
        // dd($this);
        return Form::select($this->name, $this->list, $this->value,
            $this->getOptions([]));
    }
}
