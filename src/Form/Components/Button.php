<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Button extends Link
{
    protected function render()
    {
        if($this->type == 'submit') return Form::submit($this->title, $this->getOptions([]));
        return parent::render();
    }
}
