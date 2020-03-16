<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Label extends ElementContainer
{

    protected function read(array $element)
    {
        parent::read($element);
    }

    public function render()
    {
        if(count($this->elements) == 0) return;
        return parent::render();
    }

}
