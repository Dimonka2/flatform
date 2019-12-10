<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use \ReflectionClass;

class Context implements IContext
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

    public function renderTag(IElement $element, $text = null)
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
        return $html;
    }

    public function renderElement(IElement $element, $aroundHTML)
    {
        // todo get template from config
        if($element->getTag() == '_text') return $element->getText();

        return self::renderTag($element, $aroundHTML);
    }
}
