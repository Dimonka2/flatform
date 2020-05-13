<?php

namespace dimonka2\flatform\Form\Components;

class Dropdown extends Button
{
    public $shadow;
    public $direction;
    public $group_class;
    public $dropdown_class;
    protected $drop_form;

    protected function read(array $element)
    {
        $this->items_in_title = false;
        $this->readSettings($element, ['shadow', 'direction', 'group_class', 'drop_form', 'dropdown_class']);
        parent::read($element);
        $this->attributes['data-toggle'] = "dropdown";
        $this->attributes['aria-expanded'] = "false";
    }

    public function renderButton()
    {
        return $this->render();
    }

    public function hasForm()
    {
        return !!$this->drop_form;
    }

    public function renderDropForm()
    {
        return $this->renderItem($this->drop_form);
    }

}
