<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Element;

class Breadcrumbs extends Element
{
    public $items;
    public $nav_right;

    public function read(array $element)
    {
        $this->readSettings($element, ['items', 'nav-right']);
        parent::read($element);
    }

}
