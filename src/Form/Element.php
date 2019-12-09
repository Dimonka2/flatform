<?php

namespace dimonka2\platform\Form;

class Element
{
    protected $type;
    protected $hidden;
    protected $id;
    protected $class;
    protected $style;
    protected $attributes = [];
    protected $_surround;

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
        $this->readSettings($element, explode(',', '_surround,style,class,id,type,hidden'));
        if(!is_null($this->attributes['hidden'])) $this->attributes['hidden'] = !!$this->attributes['hidden'];
        $this->attributes = $element;
    }

    public function __construct(array $element, Context $context)
    {
        $this->read($element, $context);
    }

}
