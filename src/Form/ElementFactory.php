<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Form;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use \ReflectionClass;

class ElementFactory
{
    private const tag_template = '/[_-a-zA-Z0-9]+/';
    private $context;
    private $user_binds = [];
    private $binds = [];

    public function __construct(IContext $context)
    {
        $this->context = $context;
        $this->user_binds = config('flatform.bindings');
        $this->binds = ElementMapping::bindings;
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

    protected static function mergeTemplate($element, $template)
    {
        if(is_null($template)) return $element;
        self::transferIndexedElement($element, $template, '+style', ';');
        self::transferIndexedElement($element, $template, '+class', ' ');
        self::transferIndexedElement($element, $template, 'template', ';');
        return array_merge($element, $template);
    }



    public function createElement(array $element): IElement
    {
        $def_type = config('flatform.form.default-type', 'div');
        if (isset($element['template'])) {
            // template is already given
            $template = $element['template'];
            if ($template != false && preg_match(self::tag_template, $template)) {
                $element = self::mergeTemplate($element, $this->context->getTemplate($template));
            }
        }

        $type = strtolower($element['type'] ?? $def_type);

        // use type as a template
        if ($element['template'] ?? true != false) {
            $element = self::mergeTemplate($element, $this->context->getTemplate($type));
        }

        if( ($element['template'] ?? true != false) && in_array($type, config('flatform.form.inputs', [])) ){
            // apply input template
            $element = self::mergeTemplate($element, $this->context->getTemplate('input') );
        }

        if (isset($this->user_binds[$type])) {
            return self::_createElement($this->user_binds[$type], $element, $this->context);
        }

        if (isset($this->binds[$type])) {
            return self::_createElement($this->binds[$type], $element, $this->context);
        }

        $class = $this->binds[$def_type];
        if (empty($element['type']) ) $element['type'] = $def_type;
        return self::_createElement($class, $element, $this->context);
    }

}
