<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IContext;

class Input extends Element
{
    public const input_fields = [
        'name', 'label', 'value', 'help', 'col', 'readonly', 'disabled'
    ];
    public $name;
    public $label;
    public $value;
    public $help;
    public $readonly;
    public $disabled;
    public $col;

    protected function read(array $element)
    {
        $this->readSettings($element, self::input_fields);
        parent::read($element);
        $this->requireID();
    }

    protected function getTemplate()
    {
        $template = parent::getTemplate();
        if(!is_null($template)) return $template;
        return $this->context->getTemplate('input');
    }
}
