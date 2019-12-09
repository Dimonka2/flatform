<?php

namespace dimonka2\platform\Form;

use Context;

class Element
{
    protected $type;
    protected $hidden;
    protected $id;
    protected $class;
    protected $style;
    protected $attributes = [];
    protected $_surround;
    protected $_text;

    protected function readSettings(array &$element, array $keys)
    {
        foreach($keys as $key){
            if (isset($element[$key])) {
                $value = $element[$key];
                unset($element[$key]);
                $this->$key = $value;
            }
        }
    }

    protected function read(array $element, Context $context)
    {
        $this->readSettings($element, explode(',', '_surround,_text,style,class,id,type,hidden'));
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        $this->attributes = $element;
    }

    public function __construct(array $element, Context $context)
    {
        $this->read($element, $context);
    }

    protected function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach($keys as $key){
            if(isset($this->$key) && !is_null($this->$key)) $options[$key] = $this->$key;
        }
        return $options;
    }

    protected function render(Context $context)
    {
        // special case
        if($this->type == '_text') return $this->_text;
        return $context->renderElement($this);
    }

    public function getTag()
    {
        return $this->type;
    }
}
