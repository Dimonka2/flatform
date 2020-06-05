<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Element;

class TableFilter extends Element
{
    protected $table;

    protected $name;           // field name, assigned to input
    protected $title;          // filter title or label
    protected $type;           // filter type: checkbox, select, text
    protected $disabled;       // disables filter
    protected $list;           // item list for select
    protected $filterFunction; // filter callback

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'name', 'title', 'type', 'disabled', 'list', 'filterFunction'
        ]);
        return parent::read($definition);
    }

    public function renderFilter($value)
    {
        switch ($this->type) {
            case 'checkbox':
                $checkbox = array_merge($this->getOptions([]),
                    ['checkbox', 'label' => $this->title, 'wire:model' => 'filtered.' . $this->name, ]);
                if($this->default) $checkbox['value'] = $this->default;
                return Flatform::render([
                    ['div', 'class' => 'w100', [$checkbox]]
                ]);
            case 'select':
                $select = array_merge(
                    ['select', 'label' => $this->title,
                        'selected' => $value,
                        'wire:model' => 'filtered.' . $this->name,
                        'col' => 12, 'list' => $this->list],
                        $this->getOptions([]));
                // if($this->default) $select['value'] = $this->default;
                return Flatform::render([ $select ]);
        }
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
}
