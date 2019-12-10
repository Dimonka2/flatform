<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Submit extends Input
{
    protected function render(IContext $context, $aroundHTML)
    {
        return Form::submit($this->name, $this->getOptions(['id', 'class', 'style']));
    }
}
