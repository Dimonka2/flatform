<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Dropdown extends ElementContainer
{
    public $toggle;
    public $shadow;
    public $title;
    public $direction;

    protected function read(array $element)
    {
        $this->readSettings($element, ['toggle', 'shadow', 'title', 'direction']);
        parent::read($element);
    }

    protected function render()
    {
        return $this->renderItems();
    }

}
