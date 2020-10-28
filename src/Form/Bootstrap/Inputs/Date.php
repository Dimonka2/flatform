<?php

namespace dimonka2\flatform\Form\Bootstrap\Inputs;

use dimonka2\flatform\Form\Bootstrap\Input;

class Date extends Input
{
    protected const assets = 'datepicker';

    public function getTag()
    {
        return 'text';
    }
}
