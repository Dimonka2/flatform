<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IContainer;

class ElementContainer extends Element implements IContainer, \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $elements = [];
    protected $container;

    protected function read(array $element)
    {
        $this->readSettings($element, ['text']);

        if(isset($element['items'])) {
            $items = $element['items'];
            unset($element['items']);
        }
        parent::read($element);

        // do not read items if hidden
        if(!$this->hidden && isset($items)) {
            // allow callable be an item list
            if($items instanceof \Closure) $items = $items($this);
            $this->readItems($items);
        }


        // echo $this->hash() . " Read items: \r\n";
        return $this;
    }

    public function readItems(array $items, $reset = false)
    {
        if ($reset) $this->elements = [];
        foreach ($items as $item) {
            if(is_array($item)){
                $item = $this->createElement($item);
            }
            if($item instanceof IElement) $this[] = $item;
        }
        return $this;
    }

    public function addTextElement($text)
    {
        $item = $this->createElement(['text' => $text], '_text');
        $this->elements[] = $item;
        return $item;
    }

    // add some form elements
    public function addText($name)
    {
        $item = $this->createElement(['name' => $name], 'text');
        $this->elements[] = $item;
        return $item;

    }

    // IContainer inteface

     public function render()
    {
        $html = $this->text . $this->renderItems();
        if($this->container) return $html;
        if($html == '') $html = null;
        return $this->renderer()->renderElement($this, $html);
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

    /**
     * Get the value of container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set the value of container
     *
     * @return  self
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }
}
