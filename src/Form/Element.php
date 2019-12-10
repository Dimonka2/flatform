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
    protected $_surround;
    protected $surround = null;
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

    protected function read(array $element, IContext $context)
    {
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

    public function setSurround(IElement $element)
    {
        if(is_object($this->surround)) {
            $this->surround->setSurround( $element );
        } else {
            $this->surround = $element;
        }
    }

    protected function pushSurround(array $element, IContext $context)
    {
        $item = $context->createElement($element);
        $item->read($element, $context);
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

    public function getText()
    {
        return $this->text;
    }

}
