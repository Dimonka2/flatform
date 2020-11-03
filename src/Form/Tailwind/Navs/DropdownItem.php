<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

class DropdownItem extends Link
{
    public $active;

    protected function read(array $element)
    {
        $this->readSettings($element, ['active',]);
        parent::read($element);
        if($this->active) {
            $this->applyTemplate('dd-active');
        }
    }

    protected function renderTitle()
    {
        $title = parent::renderTitle();
        return $this->renderItem([['span', 'text' => $title, 'class' => 'flex-grow text-left']]);
    }

}
