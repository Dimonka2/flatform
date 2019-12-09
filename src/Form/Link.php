<?php

namespace dimonka2\platform\Form;

use ElementContainer;
use Context;
use Illuminate\Support\Collection;

class Link extends ElementContainer
{
    protected $href;
    protected $post;

    public function read(array $element, Context $context)
    {
        $this->readSettings($element, ['href', 'post']);
    }
}
