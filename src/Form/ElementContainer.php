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

    public function getElements()
    {
        return $this->elements;
    }

    protected function read(array $element)
    {
        if(isset($element['items'])) {
            $this->readItems($element['items']);
            unset($element['items']);
        }
        $this->readSettings($element, ['text']);
        parent::read($element);
        if(!is_null($this->text)) {
            $this->addTextElement( $this->text);
        }
        // echo $this->hash() . " Read items: \r\n";

    }

    public function readItems(array $items)
    {
        foreach ($items as $item) {
            $item = $this->createElement($item);
            $this->elements->push($item);
        }
    }

    protected function addTextElement($text)
    {
        $item = $this->createElement(['type' => '_text']);
        $item->text = $text;
        $this->elements->push($item);
        return $item;
    }

    // IContainer inteface

    public function items()
    {
        return $this->elements;
    }

    protected function render()
    {
        if($this->hidden) return;
        // special case
        $aroundHTML = $this->renderItems();
        return $this->context->renderElement($this, $aroundHTML);
    }

    public function renderItems()
    {
        if(is_null($this->elements)) return;
        $html = '';
        foreach($this->elements as $element) {
             $html .= $element->renderElement( null);
        }
        return $html;
    }

}
