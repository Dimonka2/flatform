<?php

namespace dimonka2\flatform\Form\Bootstrap\Components;

use dimonka2\flatform\Form\ElementContainer;

class Progress extends ElementContainer
{
    public $color;
    public $position;
    public $striped;
    public $animated;
    public $height;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'position', 'striped', 'animated', 'height']);
        parent::read($element);
    }

}
