<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

use dimonka2\flatform\Form\ElementFactory;

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
        $element = $this->context->mergeTemplate($element, 'button');
        $this->readSettings($element, ['shadow', 'direction', 'group_class', 'drop_form', 'dropdown_class']);
        parent::read($element);
    }

    public function renderButton()
    {
        return $this->render();
    }

    public function renderDropForm()
    {
        if(!$this->drop_form) return;
        return $this->renderItem($this->drop_form);
    }

}
