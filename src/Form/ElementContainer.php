<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Element;
use dimonka2\platform\Form\Context;
use Illuminate\Support\Collection;
use dimonka2\platform\Form\Contracts\IContainer;

class ElementContainer extends Element implements IContainer
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

    public function readItems(array $items, Context $context)
    {
        $this->elements = new Collection;
        foreach ($items as $item) {
            $item = $context->createElement($item);
            $this->elements->push($item);
        }
    }

    // IContainer inteface

    public function items()
    {
        return $this->elements;
    }

    public function renderItems($context)
    {
        $html = '';
        foreach($this->elements as $element) {
            if(!$element->getHidden()) {
                $html .= $element->render($context);
            }
        }
        return $html;
    }

}
