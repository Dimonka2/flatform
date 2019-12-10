<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Element;
use dimonka2\platform\Form\ElementFactory;
use dimonka2\platform\Form\ElementContainer;
use dimonka2\platform\Form\Contracts\IContainer;
use \ReflectionClass;

class Context
{
    protected $elements = [];
    protected $next_id = 100;
    private $factory;


    public function __construct(array $elements = [])
    {
        $this->factory = new ElementFactory;
        $this->elements = new ElementContainer([], $this);
        $this->elements->readItems($elements, $this);
    }

    public function getID($name)
    {
        return $this->next_id++;
    }

    public function createElement(array $element)
    {
        return $this->factory->createElement($element, $this);
    }

    public function render()
    {
        // dd($this->elements);
        return $this->elements->renderItems($this);
    }

    public function renderTag(Element $element, $text = null)
    {
        $tag = $element->getTag();
        $html = '<' . $tag;
        $options = $element->getOptions(['id', 'class', 'style']);
        foreach($options as $key => $value) {
            if(!is_array($value)) $html .= ' ' . $key . '="' . htmlentities($value) . '"';
        }
        if(is_null($text)) {
            $html .= ' />';
        } else {
            $html .= '>' . $text . '</' . $tag . '>';
        }
        $surround = $element->getSurround();
        if(is_array($surround)) return $this->renderTag($surround, $html);
        
        return $html;
    }

    public function renderElement(Element $element)
    {
        // todo get template from config
        if($element->getTag() == '_text') return $element->getText();
        if ($element instanceof IContainer) {
            $html = $element->renderItems($this);
            return self::renderTag($element, $html);
        } else {
            return self::renderTag($element);
        }
    }
}
