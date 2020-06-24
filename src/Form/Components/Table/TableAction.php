<?php

namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Traits\SettingReaderTrait;

class TableAction
{
    use SettingReaderTrait;
    protected $table;

    protected $name;            // action unique name
    protected $position;        // array where action is rendered: 'selection', 'row'
    protected $title;           // action title or label
    protected $type;            // action type: link, button, dd-item
    protected $disabled;        // disables action
    protected $callback;        // action callback
    protected $attributes;      // all other element

    public function __construct($action)
    {
        $this->read($action);
    }

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'name', 'position', 'title', 'type', 'disabled', 'callback'
        ]);
        $this->attributes = $definition;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function getElement()
    {
        $element =  ['type' => $this->type, 'title' => $this->title, ];
        $element = array_merge($this->attributes, $element);
        return $element;
    }

    public function isSelection()
    {
        return is_array($this->position) ? in_array('selection',  $this->position) : false;
    }

    public function isRow()
    {
        return is_array($this->position) ? in_array('row',  $this->position) : false;
    }

}
