<?php

namespace dimonka2\flatform\Form\Components;

class Dropdown extends Button
{
    public $shadow;
    public $direction;
    public $group_class;

    protected function read(array $element)
    {
        $this->items_in_title = false;
        $this->readSettings($element, ['shadow', 'direction', 'group_class']);
        parent::read($element);
    }

    public function renderButton()
    {
        return $this->render();
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        $options['data-toggle'] = "dropdown";
        $options['aria-expanded'] = "false";
        return $options;
    }

}
