<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IContext;

class Input extends Element
{
    public const input_fields = [
        'name', 'label', 'value', 'help',
        'error', 'col', 'readonly', 'disabled', 'required',
    ];
    public $name;
    public $label;
    public $value;
    public $help;
    public $error;
    public $readonly;
    public $disabled;
    public $required;
    public $col;

    protected function read(array $element)
    {
        $this->readSettings($element, self::input_fields);
        parent::read($element);
        $this->requireID();
        if(!is_null($this->name) && $this->context->getErrors()->has($this->name)) {
            // add error
            $this->error = \implode('<br/>', $this->context->getErrors()->get($this->name));
            //dd($this->error);
            $template = $this->getTemplate('error-class');
            if(!is_null($template)) $this->processAttributes($template);
        }
    }

    protected function getTemplate($tag = null)
    {
        $template = parent::getTemplate($tag);
        if(!is_null($template)) return $template;
        return $this->context->getTemplate('input');
    }

    public function getOptions(array $keys)
    {
        return parent::getOptions(array_merge($keys, ['name', 'readonly', 'disabled', 'required', 'value']));
    }

    public function renderElement()
    {
        if(!$this->hidden) {
            $html = parent::renderElement();
            if ($this->col === false) return $html;
            if(is_array($this->col)) {
                $col = $this->createElement($this->col, 'col');
            } else {
                $col = $this->createElement(
                    ['col', 'col' => $this->col ? $this->col : 6, '+class' => 'form-group', ]);
            }
            return $this->context->renderElement($col, $html);
        }
    }

    public function isVertical()
    {
        return false;
    }
}
