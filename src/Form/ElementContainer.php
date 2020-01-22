<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use Illuminate\Support\Collection;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IContainer;

class ElementContainer extends Element implements IContainer, \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $elements;

    public function __construct(array $element, IContext $context)
    {
        $this->elements = new Collection;
        parent::__construct($element, $context);
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
        return $this;
    }

    public function readItems(array $items)
    {
        foreach ($items as $item) {
            $item = $this->createElement($item);
            $this[] = $item;
        }
        return $this;
    }

    protected function addTextElement($text)
    {
        $item = $this->createElement(['type' => '_text']);
        $item->text = $text;
        $this->elements[] = $item;
        return $item;
    }

    // IContainer inteface

    protected function render()
    {
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

    /**
     * Implements Countable.
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Implements IteratorAggregate.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    public function offsetSet($offset, $item) {
        if($item instanceof IElement) $item->setParent($this);
        if (is_null($offset)) {
            $this->elements[] = $item;
        } else {
            $this->elements[$offset] = $item;
        }
    }

    public function offsetExists($offset) {
        return isset($this->elements[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->elements[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->elements[$offset]) ? $this->elements[$offset] : null;
    }
}
