<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IContext;

class Image extends Element
{
    protected $src;
    protected function read(array $element)
    {
        $this->readSettings($element, ['src']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        return parent::getOptions(array_merge($keys, ['src']));
    }
}
