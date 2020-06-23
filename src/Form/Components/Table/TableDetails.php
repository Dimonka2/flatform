<?php

namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Traits\SettingReaderTrait;

class TableDetails
{
    use SettingReaderTrait;

    protected const default_expander = ['button', 'color' => 'clean',
        'size' => 'sm', 'class' => 'btn-icon-md', [
            ['i', 'class' =>'fa fa-caret-down']
        ]];
    protected $table;
    protected $expander;
    protected $callback;
    protected $disabled;
    protected $title;
    protected $class;
    protected $width;


    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function read($definition)
    {
        $this->readSettings($definition, [
            'expander', 'callback', 'disabled', 'title', 'class', 'width',
        ]);
    }

    public function render($row)
    {
        $html = 'No detail callbak!';
        if($this->callback instanceof Closure) $html = ($this->callback)($row);
        return $this->table->renderItem([
            ['tr', [
                ['td', 'colspan' => $this->table->getVisibleColumnCount(), 'text' => $html]]]
        ]);
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

    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Get the value of expander
     */
    public function getExpander()
    {
        return $this->expander ? $this->expander : $this::default_expander;
    }

    /**
     * Set the value of expander
     *
     * @return  self
     */
    public function setExpander($expander)
    {
        $this->expander = $expander;

        return $this;
    }
}
