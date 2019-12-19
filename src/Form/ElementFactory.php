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

        if (isset($element['template'])) {
            // template is already given
            $template = $element['template'];
            if ($template != false) {
                $template = $this->context->getTemplate($template);
                if(is_array($template)) {
                    $element = array_merge($template, $element);
                }
            }
        }

        $type = $element['type'] ?? $def_type;
        // use type as a template
        if (!isset($element['template']) || $element['template'] != false) {
            $template = $this->context->getTemplate($type);
            if(is_array($template)) {
                $element = array_merge($template, $element);
            }
            // important
            if(isset($template['type'])) $element['type'] = $template['type'];
        }
        // debug($element);
        if (isset($this->binds[$type])) {
            return self::_createElement($this->binds[$type], $element, $this->context);
        }


        $class = $this->binds[$def_type];
        if (empty($element['type']) ) $element['type'] = $def_type;
        return self::_createElement($class, $element, $this->context);
    }

}
