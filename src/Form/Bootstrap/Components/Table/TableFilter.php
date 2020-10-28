<?php

namespace dimonka2\flatform\Form\Bootstrap\Components\Table;

use Closure;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Element;

class TableFilter extends Element
{
    protected $table;

    protected $name;           // field name, assigned to input
    protected $title;          // filter title or label
    protected $type;           // filter type: checkbox, select, text
    protected $disabled;       // disables filter
    protected $value;          // current value
    protected $list;           // item list for select, might be a closure
    protected $filterFunction; // filter callback

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'name', 'title', 'type', 'disabled', 'value', 'list', 'filterFunction'
        ]);
        return parent::read($definition);
    }

    public function renderFilter($value)
    {
        switch ($this->type) {
            case 'checkbox':
                $checkbox = array_merge($this->getOptions([]),
                    ['checkbox', 'label' => $this->title, 'wire:model' => 'filtered.' . $this->name, ]);
                return Flatform::render([
                    ['div', 'class' => 'col-md-12', [$checkbox]]
                ]);
            case 'text':
                return Flatform::render([
                    ['text', 'label' => $this->title, 'col' => 12, 'value' => $value, 'wire:model.debounce.500ms' => 'filtered.' . $this->name,]
                ]);
            case 'select':
                $list = $this->list;
                if($list instanceof Closure) $list = $list(); // allow funciton as a list parameter
                $select = array_merge(
                    ['select', 'label' => $this->title,
                        'selected' => $value,
                        'wire:model' => 'filtered.' . $this->name,
                        'col' => 12, 'list' => $list],
                        $this->getOptions([]));
                // if($this->default) $select['value'] = $this->default;
                return Flatform::render([ $select ]);
        }
    }

    public function apply($query, $value)
    {
        $function = $this->filterFunction;
        if($function instanceof Closure) return $function($query, $value);
        // nothing to apply
        return $query;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of disabled
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set the value of disabled
     *
     * @return  self
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get the value of list
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Set the value of list
     *
     * @return  self
     */
    public function setList($list)
    {
        $this->list = $list;

        return $this;
    }

    /**
     * Get the value of filterFunction
     */
    public function getFilterFunction()
    {
        return $this->filterFunction;
    }

    /**
     * Set the value of filterFunction
     *
     * @return  self
     */
    public function setFilterFunction($filterFunction)
    {
        $this->filterFunction = $filterFunction;

        return $this;
    }

    /**
     * Get the value of table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set the value of table
     *
     * @return  self
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the value of value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
