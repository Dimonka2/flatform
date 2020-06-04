<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

use Form;

class Select extends Input
{
    protected $state_list;
    protected $list;
    protected $selected;
    protected $multiple;

    protected function read(array $element)
    {
        // debug($element);
        $this->readSettings($element, ['state-list', 'list', 'selected', 'multiple']);
        parent::read($element);
        if(is_null($this->list) && !is_null($this->state_list)) {
            // a temporary feature
            if(class_exists ('\App\Helpers\StateHelper') ) {
                $this->list = \App\Helpers\StateHelper::selectStateList($this->state_list);
            }
        }
    }

    protected function renderSingleOption($key, $text, $selected)
    {
        $option = ['value' => $key];
        if ($this->isSelected($key, $selected)) $option['selected'] = '';
        return $this->context->renderArray($option, 'option', $text);
    }

    protected function isSelected($key, $selected)
    {
        if(!$selected) return false;
        if(is_array($selected)) return !! ($selected[$key] ?? false);
        return $key == $selected;
    }

    protected function renderOptions($list)
    {
        if($this->selected) {
            $selected = $this->selected;
        } else {
            $selected = $this->value ? $this->value : $this->needValue();
        }
        $html = '';
        if($this->placeholder ?? false) $html .= $this->renderSingleOption("", $this->placeholder, $this->isSelected("", $selected));
        if(is_iterable($list)) {
            foreach ($list as $key => $option) {
                $html .= $this->renderSingleOption($key, $option, $this->isSelected($key, $selected));
            }
        }

        return $html;
    }

    public function render()
    {
        $items = $this->list;
        if($items instanceof \Closure) $items = $items($this);
        $options = $this->getOptions(['multiple']);
        return $this->context->renderArray($options, 'select', $this->renderOptions($items));
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
