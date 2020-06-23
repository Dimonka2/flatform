<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Traits\SettingReaderTrait;

class TableSelect
{
    use SettingReaderTrait;

    protected $table;
    protected $checkbox;
    protected $headerCheckbox;
    protected $column;
    protected $disabled;
    protected $width;
    protected $selectCallback;

    protected $class;
    protected $actions; // set of table actions associated with selected elements

    public function read($definition)
    {
        $this->readSettings($definition, [
            'checkbox', 'headerCheckbox', 'column', 'disabled', 'class', 'width',
            'selectCallback',
        ]);
    }

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function render($row)
    {
        $checkbox = $row ? $this->checkbox : $this->headerCheckbox;
        return $this->table->renderItem([$checkbox]);
    }

    public function __get($property)
    {
        return $this->$property;
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
     * Get the value of checkbox
     */
    public function getCheckbox()
    {
        return $this->checkbox;
    }

    /**
     * Set the value of checkbox
     *
     * @return  self
     */
    public function setCheckbox($checkbox)
    {
        $this->checkbox = $checkbox;

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
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->render(null);
    }


    /**
     * Get the value of class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the value of class
     *
     * @return  self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the value of headerCheckbox
     */
    public function getHeaderCheckbox()
    {
        return $this->headerCheckbox;
    }

    /**
     * Set the value of headerCheckbox
     *
     * @return  self
     */
    public function setHeaderCheckbox($headerCheckbox)
    {
        $this->headerCheckbox = $headerCheckbox;

        return $this;
    }

    /**
     * Get the value of actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set the value of actions
     *
     * @return  self
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get the value of getSelectCallback
     */
    public function getSelectCallback()
    {
        return $this->selectCallback;
    }

    /**
     * Set the value of getSelectCallback
     *
     * @return  self
     */
    public function setSelectCallback($getSelectCallback)
    {
        $this->selectCallback = $getSelectCallback;

        return $this;
    }
}
