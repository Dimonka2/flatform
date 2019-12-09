<?php

namespace dimonka2\platform\Form;

use dimonka2\platform\Form\Element;
use dimonka2\platform\Form\ElementContainer;
use dimonka2\platform\Form\Contracts\IContainer;
use \ReflectionClass;

class ElementFactory
{
    private $binds = [
        'text' => Inputs\Text::class,
        'password' => Inputs\Password::class,
        'number' => Inputs\Number::class,        
        'textarea' => Inputs\Textarea::class,  
        'summernote' => Inputs\Summernote::class,        
        'select' => Inputs\Select::class,   
        'select2' => Inputs\Select2::class,   
        'file' => Inputs\File::class, 
        'radio' => Inputs\Radio::class,   
        'date' => Inputs\Date::class,        
        
        'div' => ElementContainer::class,
        'span' => ElementContainer::class,
        'i' => ElementContainer::class,
        'b' => ElementContainer::class,
        'u' => ElementContainer::class,
        'ul' => ElementContainer::class,
        'li' => ElementContainer::class,
        '_text' => Element::class,
    ];

    public function createElement(array $element, $context)
    {
        $def_type = config('platform.form.default-type', 'div');
        $type = strtolower($element['type'] ?? $def_type);
        $class = isset($this->binds[$type]) ? $this->binds[$type] : $this->binds[$def_type];
        // make class
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs([$element, $context]);
    }

}