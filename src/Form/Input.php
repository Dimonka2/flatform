<?php

namespace dimonka2\platform\Form;

use Element;

class Input extends Element
{

    protected $name;
    protected $required;
    protected $disabled;
    protected $readonly;
    protected $placeholder;
    protected $label;
    protected $value;

    protected function read(array $element, Context $context)
    {
        $fields = 'name,required,disabled,readonly,placeholder,label,value';
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
