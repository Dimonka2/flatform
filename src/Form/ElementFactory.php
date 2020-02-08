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

    protected static function _createElement(array $element, $context): IElement
    {
        $reflection = new ReflectionClass($element['binding']);
        unset($element['binding']);
        return $reflection->newInstanceArgs([$element, $context]);
    }

    protected static function transferIndexedElement(&$element1, &$element2, $index, $delimiter)
    {
        if(isset($element2[$index])) {
            $element1[$index] = (isset($element1[$index]) ? $element1[$index] . $delimiter : '') . $element2[$index];
            unset($element2[$index]);
        }
    }

    protected function mergeTemplate($element, $template)
    {
        if(is_null($template)) return $element;
        self::transferIndexedElement($element, $template, '+style', ';');
        self::transferIndexedElement($element, $template, '+class', ' ');
        self::transferIndexedElement($element, $template, 'template', ';');
        $type = $element['type'] ?? null;
        if(empty($element['binding']) && $type) {
            $binding = $this->getBinding($type);
            if($binding) {
                $element['binding'] = $binding;
            }
        }
        $element = array_merge($element, $template);
        $new_type = $element['type'] ?? null;
        if($type != $new_type ) $element = $this->mergeTemplate($element, $this->context->getTemplate($new_type));
        return $element;
    }

    protected function getBinding($type)
    {
        if(is_null($type)) return null;
        if (isset($this->user_binds[$type])) return $this->user_binds[$type];
        if (isset($this->binds[$type])) return $this->binds[$type];
    }

    public function createElement(array $element): IElement
    {
        $def_type = config('flatform.form.default-type', 'div');
        $binding = null;

        // new syntax - first element is a type element (no need to write 'type' => ..)
        // next indexed elements are booleans or items
        if(empty($element['type']) && isset($element[0])) {
            $element['type'] = $element[0];
            unset($element[0]);
        }

        foreach($element as $key => $value) {
            // convert to the old syntax
            if(is_integer($key)) {
                unset($element[$key]);
                if(is_array($value)) {
                    $element['items'] = $value;
                } else {
                    $element[$value] = true;
                }
            }
        }

        $type = strtolower($element['type'] ?? null);
        // use type as a template
        if (($element['template'] ?? true) !== false && $type) {
            $element = $this->mergeTemplate($element, $this->context->getTemplate($type));
        }

        if( ($element['template'] ?? true !== false) && in_array($type, config('flatform.form.inputs', [])) ){
            // apply input template
            if($element['no-input'] ?? false != false) {
                unset($element['no-input']);
            } else {
                $element = $this->mergeTemplate($element, $this->context->getTemplate('input') );
            }
        }

        // take element type again
        if(!isset($element['binding'])) {
            $type = strtolower($element['type'] ?? $def_type);
            $binding = $this->getBinding($type);
            if($binding) {
                $element['binding'] = $binding;
                return self::_createElement($element, $this->context);
            }
        } else return self::_createElement($element, $this->context);

        $element['binding'] = $this->binds[$def_type];
        if (empty($element['type']) ) $element['type'] = $def_type;
        return self::_createElement($element, $this->context);
    }

}
