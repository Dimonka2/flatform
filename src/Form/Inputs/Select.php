<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Select extends Input
{
    protected $state_list;
    protected $list;
    protected $selected;

    protected function read(array $element)
    {
        $this->readSettings($element, ['state-list', 'list', 'selected']);
        parent::read($element);
        if(is_null($this->list) && !is_null($this->state_list)) {
            // a temporary feature
            if(class_exists ('\App\Helpers\StateHelper') ) {
                $this->list = \App\Helpers\StateHelper::selectStateList($this->state_list);
            }
        }
    }

    protected function render()
    {
        return Form::select($this->name, $this->list ?? [], $this->value,
            $this->getOptions(['placeholder', 'readonly', 'disabled']));
    }

        /**
     * Get the value of selected
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set the value of selected
     *
     * @return  self
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }
}
