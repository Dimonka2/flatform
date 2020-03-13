<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Form\Element;

class DTFilter extends Element
{
    public const filterClass = 'dt-filter';
    protected $name;           // field name, assigned to input
    protected $title;          // filter title or label
    protected $filter;         // filter type: checkbox, select, text
    protected $disabled;       // disables filter
    protected $default;        // default value
    protected $list;           // item list for select
    protected $filterFunction; // filter callback

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'name', 'title', 'filter', 'disabled', 'list', 'filterFunction'
        ]);
        return parent::read($element);
    }

    public function form()
    {
        switch ($this->filter) {
            case 'checkbox':

                $checkbox = array_merge($this->getOptions([]),
                    ['checkbox', 'label' => $this->title, 'name' => $this->name,
                        '+class' => self::filterClass]);
                if($this->default) $checkbox['value'] = $this->default;
                return ['col', 'col' => 12, [$checkbox]];
            case 'select':
                $select = array_merge($this->getOptions([]),
                    ['select', 'label' => $this->title, 'name' => $this->name,
                        'col' => 12, 'list' => $this->list,
                        '+class' => self::filterClass]);
                if($this->default) $select['value'] = $this->default;
                return $select;
        }
    }

    public function apply($query, $data)
    {
        if(is_callable($this->filterFunction)) call_user_func_array($this->filterFunction, [$query, $data]);
    }

    public function isEnabled()
    {
        return !$this->disabled && is_callable($this->filterFunction);
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
     * Get the value of filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set the value of filter
     *
     * @return  self
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

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
}
