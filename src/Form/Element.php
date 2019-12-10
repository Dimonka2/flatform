<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Context;

class Element
{
    private $type;
    protected $hidden;
    protected $id;
    protected $class;
    protected $style;
    protected $attributes = [];
    protected $_surround;
    protected $text;

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
        $this->readSettings($element, explode(',', '_surround,text,style,class,id,type,hidden'));
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        $this->attributes = $element;
    }

    public function __construct(array $element, Context $context)
    {
        $this->read($element, $context);
    }

    public function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach($keys as $key){
            if(isset($this->$key) && !is_null($this->$key)) $options[$key] = $this->$key;
        }
        return $options;
    }

    public function render(Context $context)
    {
        if($this->hidden) return;
        // special case
        if($this->type == '_text') return $this->text;
        return $context->renderElement($this);
    }

    public function getTag()
    {
        return $this->type;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getSurround()
    {
        return $this->_surround;
    }

    public function getHidden()
    {
        return $this->hidden;
    }
}
