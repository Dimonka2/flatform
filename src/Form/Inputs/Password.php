<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Password extends Input
{
    protected function render(IContext $context, $aroundHTML)
    {
        return Form::password($this->name, $this->value,
            $this->getOptions(['id', 'class', 'style']));
    }
}
