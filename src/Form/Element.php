<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class Element implements IElement
{
    private $type;
    protected $hidden;
    protected $id;
    protected $class;
    protected $style;
    protected $attributes = [];
    protected $_surround = null;
    protected $surround = null;
    protected $text;

    protected function hash()
    {
        return substr(spl_object_hash($this), 11, 5);
    }

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

    protected function read(array $element, IContext $context)
    {
        // echo $this->hash() . " Reading new element: \r\n";
        // print_r($element);
        $this->readSettings($element, explode(',', '_surround,text,style,class,id,type,hidden'));
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        if(!is_null($this->_surround)) $this->pushSurround($this->_surround, $context);
        $this->attributes = $element;
    }

    public function __construct(array $element, IContext $context)
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

    public function setSurround(IElement $surround)
    {
        // echo $this->hash() . ": Element.setSurround \r\n";
        if(is_object($this->surround)) {
            $this->surround->setSurround( $surround );
        } else {
            $this->surround = $surround;
        }
    }

    protected function pushSurround(array $surround, IContext $context)
    {
        // echo $this->hash() . ": Element.pushSurround \r\n";
        $item = $context->createElement($surround);
        $this->setSurround($item);
    }

    public function renderElement(IContext $context, $aroundHTML)
    {
        if(!$this->hidden) {
            $html = $this->render($context, $aroundHTML);
            if(is_object($this->surround)) {
                $html = $this->surround->renderElement($context, $html);
            }
            return $html;
        }
    }

    protected function render(IContext $context, $aroundHTML)
    {
        if($this->hidden) return;
        // special case
        if($this->type == '_text') return $this->text;
        return $context->renderElement($this, $aroundHTML);
    }

    public function getTag()
    {
        return $this->type;
    }


}
