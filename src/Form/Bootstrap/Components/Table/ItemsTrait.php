<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table;

use dimonka2\flatform\Form\Contracts\IElement;

trait ItemsTrait
{
    protected $items;
    /**
    * Implements Countable.
    */
    public function count()
    {
        return $this->items->count();
    }

    public function clear()
    {
        $this->items = collect();
    }

    /**
     * Implements IteratorAggregate.
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    public function offsetSet($offset, $item) {
        if($item instanceof IElement) $item->setParent($this->table);
        if (is_null($offset)) {
            $this->items[] = $item;
        } else {
            $this->items[$offset] = $item;
        }
    }

    public function offsetExists($offset) {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
}
