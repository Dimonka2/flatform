<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Tailwind\Inputs\Input;

class Date extends Input
{
    protected const assets = 'datepicker';

    public function getTag()
    {
        return 'text';
    }
}
