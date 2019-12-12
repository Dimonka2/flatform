<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Form;
use dimonka2\flatform\Form\Contracts\IContext;
use \ReflectionClass;

class ElementFactory
{
    private $context;
    private $binds = [
        // inputs
        'text' => Inputs\Text::class,
        'password' => Inputs\Password::class,
        'number' => Inputs\Number::class,
        'textarea' => Inputs\Textarea::class,
        'summernote' => Inputs\Summernote::class,
        'select' => Inputs\Select::class,
        'select2' => Inputs\Select2::class,
        'file' => Inputs\File::class,
        'checkbox' => Inputs\Checkbox::class,
        'radio' => Inputs\Radio::class,
        'date' => Inputs\Date::class,
        'hidden' => Inputs\Hidden::class,

        // components
        'tabs' => Components\Tabs::class,
        'widget' => Components\Widget::class,
        'dropdown' => Components\Dropdown::class,
        'dd-item' => Components\DropdownItem::class,

        // links and buttons
        'a' => Link::class,
        'submit' => Components\Button::class,
        'button' => Components\Button::class,

        'form' => Form::class,

        'div' => ElementContainer::class,
        'span' => ElementContainer::class,
        'i' => ElementContainer::class,
        'b' => ElementContainer::class,
        'u' => ElementContainer::class,
        'ul' => ElementContainer::class,
        'li' => ElementContainer::class,
        'label' => Label::class,
        '_text' => Element::class,
        '_template' => Element::class,
    ];

    public function __construct(IContext $context)
    {
        $this->context = $context;
    }

    protected static function _createElement($class, array $element, $context)
    {
        $reflection = new ReflectionClass($class);
        return $reflection->newInstanceArgs([$element, $context]);
    }

    public function createElement(array $element)
    {
        $def_type = config('flatform.form.default-type', 'div');
        $type = strtolower($element['type'] ?? $def_type);
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
        return self::_createElement($class, $element, $this->context);
    }

}
