<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Form;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
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

    protected static function _createElement($class, array $element, $context): IElement
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs([$element, $context]);
    }

    protected static function transferIndexedElement(&$element1, &$element2, $index, $delimiter)
    {
        if(isset($element2[$index])) {
            $element1[$index] = (isset($element1[$index]) ? $element1[$index] . $delimiter : '') . $element2[$index];
            unset($element2[$index]);
        }
    }

    protected static function smartMerge($element1, $element2)
    {
        self::transferIndexedElement($element1, $element2, '+style', ';');
        self::transferIndexedElement($element1, $element2, '+class', ' ');
        return array_merge($element1, $element2);
    }

    public function createElement(array $element): IElement
    {
        $def_type = config('flatform.form.default-type', 'div');

        $type = strtolower($element['type'] ?? '');
        $template = null;
        if (isset($element['template'])) {
            // template is already given
            $template = $element['template'];
            if ($template != false) {
                $template = $this->context->getTemplate($template);
            }
        } else {
            if(in_array($type, config('flatform.form.inputs', [])) ){
                // apply input template
                $template = $this->context->getTemplate('input');

            }
        }
        if(is_array($template)) {
            $element = array_merge($template, $element);
            if(isset($template['type'])) $element['type'] = $template['type'];
        }

        $template = null;
        $type = $element['type'] ?? $def_type;
        // use type as a template
        if (!isset($element['template']) || $element['template'] != false) {
            $template = $this->context->getTemplate($type);
            if(is_array($template)) $element = self::smartMerge($template, $element);

        }
        if(is_array($template)) {
            $element = array_merge($template, $element);
            if(isset($template['type'])) $element['type'] = $template['type'];
        }

        if (isset($this->binds[$type])) {
            return self::_createElement($this->binds[$type], $element, $this->context);
        }


        $class = $this->binds[$def_type];
        if (empty($element['type']) ) $element['type'] = $def_type;
        return self::_createElement($class, $element, $this->context);
    }

}
