<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;

class Dropdown extends Link
{
    public $toggle;
    public $shadow;
    public $direction;
    public $group_class;

    protected function read(array $element)
    {
        $this->items_in_title = false;
        $this->readSettings($element, ['toggle', 'shadow', 'direction', 'group_class']);
        parent::read($element);
    }

    protected function render()
    {
        return $this->renderItems();
    }

}
