<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Date extends Input
{
    protected const assets = 'datepicker';

    public function getTag()
    {
        return 'text';
    }
}
