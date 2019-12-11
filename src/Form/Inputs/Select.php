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
        $fields = 'state-list,list';
        $this->readSettings($element, explode(',', $fields));
        parent::read($element);
        if(is_null($this->list) && !is_null($this->state_list)) {
            $this->list = [];
            // a temporary feature
            if(class_exists ('\App\Helpers\StateHelper') ) {
                $this->list = \App\Helpers\StateHelper::selectStateList($this->state_list);
            }
        }
    }

    protected function render()
    {
        // dd($this);
        return Form::select($this->name, $this->list, $this->value,
            $this->getOptions([]));
    }
}
