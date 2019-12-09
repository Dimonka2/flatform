<?php

namespace dimonka2\platform\Form;

use Element;
use ElementContainer;
use ReflectionClass;

class Context
{
    protected $elements = [];
    protected $next_id = 100;

    protected $binds = [
        'div' => ElementContainer::class,
        'span' => ElementContainer::class,
        '_text' => Element::class,
    ];

    public function __construct(array $elements = [])
    {
        $elements = new ElementContainer($elements, $this);
    }

    public function getID($name)
    {
        return $this->next_id++;
    }

    public function create(array $element)
    {
        $def_type = config('platform.form.default-type');
        $type = $element['type'] ?? $def_type;
        $class = isset($this->binds[$type]) ? $this->binds[$type] : $this->binds[$def_type];
        // make class
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs($element);
    }

    public function render()
    {
        foreach($this->elements as $element) {
            $element->render($this);
        }
    }

    public static function renderElement(Element $element, $text = null)
    {
        $html = '<' . $element->getTag();
        foreach($options as $key => $value) {
            $html .= ' ' . $key . '="' . htmlentities($value) . '"';
        }
        if(is_null($text)) {
            $html .= ' />';
        } else {
            $html .= '>' . $text . '</' . $element . '>';
        }
        return $html;
    }
}
