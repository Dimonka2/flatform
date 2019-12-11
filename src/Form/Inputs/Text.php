<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;


class Text extends Input
{
    protected $placeholder;

    protected function render(IContext $context, $aroundHTML)
    {
        return Form::text($this->name, $this->value,
            $this->getOptions(['placeholder']));
    }

    protected function read(array $element, IContext $context)
    {
        $this->readSettings($element, ['placeholder']);
        parent::read($element, $context);
    }
}
