<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Form;
use dimonka2\flatform\Form\Contracts\IContext;
use \ReflectionClass;

class ElementFactory
{
    private $context;
    private $binds = [];

    public function __construct(IContext $context)
    {
        $this->context = $context;
        $this->binds = config('flatform.bindings');
    }

    protected static function _createElement($class, array $element, $context)
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs([$element, $context]);
    }

    public function createElement(array $element)
    {
        $def_type = config('flatform.form.default-type', 'div');
        $type = strtolower($element['type'] ?? '');
        if (isset($this->binds[$type])) {
            return self::_createElement($this->binds[$type], $element, $this->context);
        }
        $template = $this->context->getTemplate($type);
        if(is_array($template)) {
            $type = $template['type'] ?? $def_type;
            return self::_createElement($this->binds[$type],
                array_merge($template, $element), $this->context);
        }
        $class = $this->binds[$def_type];
        if (empty($element['type']) ) $element['type'] = $def_type;
        return self::_createElement($class, $element, $this->context);
    }

}
