<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use Form;


class Input extends Element
{
    public const input_fields = [
        'name', 'label', 'value', 'help', 'placeholder',
        'error', 'col', 'readonly', 'disabled', 'required',
    ];
    protected $defaultOptions = ['id', 'class', 'style', 'name', 'placeholder', 'value', 'disabled', 'required'];

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
        if($this->name) {
            $errors = $this->context->getError($this->name);

            // add error
            if(is_array($errors) && count($errors) > 0) {
                $this->error = implode('<br/>', $errors);
                $template = $this->getTemplate('error-class');
                if(!is_null($template)) $this->processAttributes($template);
                // debug($this->error);
            }
        }
        parent::read($element);

    }

    protected function needValue()
    {
        if($this->hasValue() ) {
            if($this->value) return $this->value;
        }
        if($this->name) {
            // try to get value from the form
            $form = $this->context->getForm();
            if($form) {
                return $form->getModelValue($this->name);
            } else {
                return Form::getValueAttribute($this->name);
            }
        }
    }

    protected function hasValue()
    {
        return true;
    }

    public function render()
    {
        $options = $this->getOptions([]);
        $options['type'] = $this->getTag();
        if($this->hasValue()) $options['value'] = $this->needValue();
        return $this->context->renderArray($options, 'input') . $this->addAssets();
    }


    protected function getTemplate($tag = null)
    {
        $template = parent::getTemplate($tag);
        if(!is_null($template)) return $template;
        return $this->context->getTemplate('input');
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
