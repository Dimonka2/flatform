<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Label extends ElementContainer
{

    protected function read(array $element, IContext $context)
    {
        parent::read($element, $context);
    }

    protected function render(IContext $context, $aroundHTML)
    {
        if($this->elements->count() == 0 && $aroundHTML == '') return;
        return parent::render($context, $aroundHTML);
    }

}
