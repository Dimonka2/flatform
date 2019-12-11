<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use \ReflectionClass;
use Closure;

class Context implements IContext
{
    protected $elements = [];
    protected $next_id = 100;
    private $factory;
    private $template = null;
    private $cofig_template_path;

    public function __construct(array $elements = [])
    {
        $this->cofig_template_path = config('flatform.form.style');
        $this->factory = new ElementFactory($this);
        $this->elements = new ElementContainer([], $this);
        $this->elements->readItems($elements, $this);
        
    }

    public function getID($name)
    {
        return $name . '-' . $this->next_id++;
    }

    public function createElement(array $element)
    {
        return $this->factory->createElement($element, $this);
    }

    public function createTemplate($template)
    {
        if($template == '') return;
        $template = $this->getTemplate($template);
        if(!is_array($template)) return;
        return $this->createElement($template);
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
        $options = $element->getOptions([]);
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

    public function renderElement(IElement $element, $aroundHTML = null)
    {
        // todo get template from config
        return $this->renderTag($element, $aroundHTML);
    }

    public function getTemplate($tag)
    {
        return config('flatform.' . $this->cofig_template_path . '.' . $tag);
    }

    public function setTemplatable(IElement $element = null)
    {
        if( isset($element)) {
            if(is_null($this->template)) {
                $this->template = $element;
                return true;
            }
        } else {
            $this->template = null;
        }
    }

    public function getTemplatable()
    {
        return $this->template;
    }
}
