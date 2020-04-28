<?php

namespace dimonka2\flatform\Form\Components\Table;

class Columns implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use ItemsTrait;
    protected $table;

    public function __construct(Table $table)
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

    public function getColumn($index): ?Column
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


}
