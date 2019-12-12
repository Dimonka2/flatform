<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IContainer;

class BladeDirective extends Element
{
    protected $directive;

    public function read(array $element)
    {
        $this->readSettings($element, ['directive']);
        parent::read($element);
    }

    protected function render()
    {
        // special case
        if(is_array($this->directive)){

        }
    }
}
