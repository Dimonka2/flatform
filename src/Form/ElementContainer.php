<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Element;
use dimonka2\platform\Form\Context;
use Illuminate\Support\Collection;
use dimonka2\platform\Form\Contracts\IContainer;

class ElementContainer extends Element implements IContainer
{
    protected $elements;

    public function __construct(array $element, Context $context)
    {
        $this->elements = new Collection;
        parent::__construct($element, $context);
    }

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
        foreach ($items as $item) {
            $item = $context->createElement($item);
            $this->elements->push($item);
        }
    }

    protected function addTextElement($context, $text)
    {
        $item = $context->createElement(['type' => '_text']);
        $item->text = $text;
        $this->elements->push($item);
        return $item;
    }

    // IContainer inteface

    public function items()
    {
        return $this->elements;
    }

    public function renderItems($context)
    {
        if(is_null($this->elements)) return;
        $html = '';
        foreach($this->elements as $element) {
            if(!$element->getHidden()) {
                $html .= $element->render($context);
            }
        }
        return $html;
    }

}
