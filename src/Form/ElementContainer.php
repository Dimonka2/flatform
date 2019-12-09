<?php

namespace dimonka2\platform\Form;

use Element;
use dimonka2\platform\Form\Context;
use Illuminate\Support\Collection;

class ElementContainer extends Element
{
    protected $elements;

    protected function read(array $element, Context $context)
    {
        if(isset($element['items'])) {
            $this->readItems($element['items'], $context);
            unset($element['items']);
        }
        parent::read($element, $context);
    }

    protected function readItems(array $items, Context $context)
    {
        $element = new Collection;
        foreach ($items as $item) {
            $this->elements->push($context->create($item));
        }
    }

}
