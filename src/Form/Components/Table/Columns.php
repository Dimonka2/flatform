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
            if($item->visible()){
                $def = ['td'];
                $def['class'] = 'text-nowrap text-truncate ';
                if($item->width) $def['style'] = 'width: ' . $item->width . ';';
                if ($item->sort) {
                    switch ($order[$item->name] ?? null) {
                        case 'ASC':
                            $sortingClass = 'fa fa-sort-up text-danger';
                            break;
                        case 'DESC':
                            $sortingClass = 'fa fa-sort-down text-danger';
                            break;

                        default:
                            $sortingClass = 'fa fa-sort text-slate-300';
                            break;
                    }
                    $def['items'] = [
                        ['a', 'href' => '#', 'class' => 'd-block',[
                            ['div', 'class' => 'float-right d-block ml-2', 'style' => '', [
                                ['i', 'class' => 'text-nowrap ' . $sortingClass],
                            ]],
                            ['div', 'class' => 'text-slate-600 d-inline-block mr-3 '  . $item->class,
                            'text' => $this->table->renderItem($item->title)],
                        ]],
                    ];
                } else {
                    $def['text'] = $this->table->renderItem($item->title);
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
