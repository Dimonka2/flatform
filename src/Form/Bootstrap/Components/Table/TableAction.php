<?php

namespace dimonka2\flatform\Form\Bootstrap\Components\Table;

use dimonka2\flatform\Traits\SettingReaderTrait;

class TableAction
{
    use SettingReaderTrait;
    protected $table;

    protected $name;            // action unique name
    protected $position;        // array where action is rendered: 'selection', 'dropdown', 'row', 'row-dd'
    protected $title;           // action title or label
    protected $disabled;        // disables action
    protected $callback;        // action callback
    protected $attributes;      // all other elements

    public function __construct($action)
    {
        $this->read($action);
    }

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'name', 'action', 'position', 'title', 'disabled', 'callback'
        ]);
        $this->attributes = $definition;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function getElement()
    {
        $element =  ['title' => $this->title, ];
        $element = array_merge($this->attributes, $element);
        return $element;
    }

    public function hasPosition($position)
    {
        $action_position = $this->position;
        return (is_array($action_position) && in_array($position, $action_position)) || $action_position == $position;
    }

    public function isSelectionAction()
    {
        return $this->hasPosition('selection') || $this->hasPosition('dropdown');
    }

    public function isRow()
    {
        return $this->hasPosition('row-inline') || $this->hasPosition('row-dd');
    }

}
