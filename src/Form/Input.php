<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Element;

class Input extends Element
{

    protected $name;
    protected $label;
    protected $value;
    protected $help;

    protected function read(array $element, Context $context)
    {
        $fields = 'name,label,value,help';
        $this->readSettings($element, explode(',', $fields));
        parent::read($element, $context);
    }

    protected function requireID(Context $context)
    {
        if(is_null($this->id)) {
            $this->id = $context->getID($this->name ?? 'id');
        }
    }
}
