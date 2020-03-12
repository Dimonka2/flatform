<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Form\Contracts\IElement;

class Columns implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $items;
    protected $table;

    public function __construct(Datatable $table)
    {
        $this->table = $table;
        $this->items = collect();
    }

    public function getColumnIndex($name)
    {
        return $this->items->search(function ($item) use($name) {
            return ($item->name == $name) or ($item->as == $name);
        });
    }

    public function getColumn($index): ?DTColumn
    {
        if(is_integer($index)) {
            return $this->items[$index];
        }
        $key = $this->getColumnIndex($index);
        if($key !== false) return $this->items[$key];
        return null;
    }

    public function getColumnEx($index, &$key)
    {
        if(is_integer($index)) {
            $key = $index;
        } else {
            $key = $this->getColumnIndex($index);
        }
        if($key !== false) return $this->items[$key];
        return;
    }

    public function setFormatFunction($formatFunction, $columnName)
    {
        if(is_iterable($columnName)) {
            foreach($columnName as $column){
                $column = $this->getColumn($column);
                if($column) $column->setFormatFunction($formatFunction);
            }
            return $this;
        }elseif($columnName) {
            $column = $this->getColumn($columnName);
            if($column) $column->setFormatFunction($formatFunction);
            return $this;
        }
    }

    /**
    * Implements Countable.
    */
    public function count()
    {
        return $this->items->count();
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
