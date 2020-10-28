<?php

namespace dimonka2\flatform\Form\Bootstrap\Inputs;

use dimonka2\flatform\Form\Bootstrap\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Password extends Input
{
    protected function hasValue()
    {
        return false;
    }
}
