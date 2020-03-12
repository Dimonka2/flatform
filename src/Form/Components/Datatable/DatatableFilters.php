<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IElement;

class DatatableFilters extends Element implements \ArrayAccess, \Countable, \IteratorAggregate
{
    protected $items;
    protected $table;

    public function getDropdown()
    {
        $filters = [];
        foreach ($this->items as $filter) {
            if($filter->isEnabled()) {
                $filters[] = $filter->form();
            }
        }

        return json_encode(Flatform::render([
            ['div', 'class' => 'd-inline ml-2 mr-2 btn-group', [
                ['dropdown', 'toggle', 'group', 'color' => 'outline-secondary', 'size' => 'sm', 'shadow',
                    'title' => [ ['span', [
                        ['include', 'name' => 'flatform::icons.filter',
                            'with' => ['width' => '1.4em', 'height' => '1.4em']]]
                    ]],
                    'drop_form' => [
                        ['div', 'style' => 'min-width:320px;', 'class' => 'p-3', [
                            ['form', 'id' => $this->table->id . '_filter-form', [
                                ['div', $filters ],
                            ]]
                        ]],
                    ]
                ]

            ]]
        ] ));
    }

    public function process($filterData, $query)
    {
        $filterData = collect(json_decode($filterData));
        foreach ($this->items as $filter) {
            if($filter->isEnabled()) {
                $data = $filterData->where('name', $filter->getName())->first();
                $filter->apply($query, $data);
            }
        }

        return $query;
    }

    public function isEnabled()
    {
        foreach ($this->items as $filter) {
            if($filter->isEnabled()) return true;
        }
    }

    public function __construct(Datatable $table)
    {
        $this->table = $table;
        $this->items = collect();
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
