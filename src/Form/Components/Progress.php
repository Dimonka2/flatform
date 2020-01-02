<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Progress extends ElementContainer
{
    public $color;
    public $position;
    public $striped;
    public $animated;
    public $size;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'position', 'striped', 'animated', 'size']);
        parent::read($element);
    }

}
