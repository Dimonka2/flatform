<?php

namespace dimonka2\flatform\Form;

use \ReflectionClass;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Bootstrap\BootstrapMapping;
use dimonka2\flatform\Form\Tailwind\TailwindMapping;

class ElementFactory
{
    private $context;
    private $user_binds = [];
    private $binds = [];
    private $tailwind = false;

    public function __construct(IContext $context, $tailwind = false)
    {
        $this->context = $context;
        $this->user_binds = FlatformService::config('flatform.bindings');
        $this->setTailwind($tailwind);
    }

    protected static function _createElement(array $element, $context): IElement
    {
        $reflection = new ReflectionClass($element['binding']);
        unset($element['binding']);
        return $reflection->newInstanceArgs([$element, $context]);
    }

    public static function transferIndexedElement(&$element1, &$element2, $index, $delimiter)
    {
        if(isset($element2[$index])) {
            $element1[$index] = (isset($element1[$index]) ? $element1[$index] . $delimiter : '') . $element2[$index];
            unset($element2[$index]);
        }
    }

    public function mergeTemplate($element, $template)
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

    public static function preprocessElement(array $element, $includeType = true): array
    {
        if($includeType) {
            if(!isset($element['type']) && isset($element[0]) && !is_array($element[0])) {
                $element['type'] = $element[0];
                unset($element[0]);
            }
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
        return $element;
    }

    public function createElement(array $element): IElement
    {
        $def_type = FlatformService::config('flatform.form.default-type', 'div');
        $binding = null;

        // new syntax - first element is a type element (no need to write 'type' => ..)
        // next indexed elements are booleans or items
        $element = self::preprocessElement($element);

        $type = strtolower($element['type'] ?? null);
        // use type as a template
        if (($element['template'] ?? true) !== false && $type) {
            $element = $this->mergeTemplate($element, $this->context->getTemplate($type));
        }

        if( ($element['template'] ?? true !== false) && in_array($type, FlatformService::config('flatform.form.inputs', [])) ){
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


    /**
     * Get the value of tailwind
     */ 
    public function getTailwind()
    {
        return $this->tailwind;
    }

    /**
     * Set the value of tailwind
     *
     * @return  self
     */ 
    public function setTailwind($tailwind)
    {
        $this->tailwind = $tailwind;
        if($tailwind) {
            $this->binds = TailwindMapping::bindings;
        }else{
            $this->binds = BootstrapMapping::bindings;
        }
        return $this;
    }
}
