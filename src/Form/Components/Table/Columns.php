<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\Contracts\IContext;

class Columns implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use ItemsTrait;
    protected $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->items = collect();
    }

    public function render(IContext $context)
    {
        $order = $this->table->getOrder();

        $out = "";
        foreach($this->items as $item) {
            if(!$item->hide){
                $def = ['th'];
                if($item->class) $def['class'] = $item->class;
                if ($item->sort) {
                    switch ($order[$item->name] ?? null) {
                        case 'ASC':
                            $sortingClass = 'fa fa-sort-amount-up';
                            break;
                        case 'DESC':
                            $sortingClass = 'fa fa-sort-amount-down';
                            break;

                        default:
                            $sortingClass = 'fa fa-sort';
                            break;
                    }
                    $def['items'] = [
                        ['a', 'href' => '#', [
                            ['_text', 'text' => $item->title . '&nbsp;'],
                            ['i', 'class' => $sortingClass],
                        ]],
                    ];
                } else {
                    $def['title'] = $item->title;
                }
                $out .= $context->renderItem([$def]);
            }
        }
        return $out;
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
                if($column) $column->setFormat($formatFunction);
            }
            return $this;
        }elseif($columnName) {
            $column = $this->getColumn($columnName);
            if($column) $column->setFormat($formatFunction);
            return $this;
        }
    }


}
