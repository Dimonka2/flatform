<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use Illuminate\Support\Collection;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IContainer;

class ElementContainer extends Element implements IContainer
{
    protected $elements;

    public function __construct(array $element, IContext $context)
    {
        $this->elements = new Collection;
        parent::__construct($element, $context);
    }

    protected function read(array $element, IContext $context)
    {
        parent::read($element, $context);
        // echo $this->hash() . " Read items: \r\n";
        if(isset($element['items'])) {
            $this->readItems($element['items'], $context);
            unset($element['items']);
        }
    }

    public function readItems(array $items, IContext $context)
    {
        foreach ($items as $item) {
            $item = $context->createElement($item);
            $this->elements->push($item);
        }
    }

    protected function addTextElement(IContext $context, $text)
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

    protected function render(IContext $context, $aroundHTML)
    {
        if($this->hidden) return;
        // special case
        $aroundHTML .= $this->renderItems($context);
        return $context->renderElement($this, $aroundHTML);
    }

    public function renderItems(IContext $context)
    {
        if(is_null($this->elements)) return;
        $html = '';
        foreach($this->elements as $element) {
            $html .= $element->renderElement($context, null);
        }
        return $html;
    }

}
