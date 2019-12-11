<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Label extends ElementContainer
{

    protected function read(array $element)
    {
        parent::read($element);
    }

    protected function render()
    {
        if($this->elements->count() == 0 && $aroundHTML == '') return;
        return parent::render();
    }

}
