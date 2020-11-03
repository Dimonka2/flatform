<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Element;


class Input extends Element
{
    public const input_fields = [
        'name', 'label', 'value', 'help', 'placeholder',
        'error', 'col', 'readonly', 'disabled', 'required', 'wire_ignore',
    ];
    protected $defaultOptions = ['id', 'class', 'style', 'name', 
        'placeholder', 'value', 'disabled', 'required'];

    public $name;
    public $label;
    public $value;
    public $help;
    public $error;
    public $readonly;
    public $wire_ignore;
    public $disabled;
    public $required;
    public $col = 3;

    protected $labelFirst = true;

    protected function read(array $element)
    {
        $this->readSettings($element, self::input_fields);
        if($this->name) {
            $errors = $this->context->getError($this->name);

            // add error
            if(is_array($errors) && count($errors) > 0) {
                if($this->error !== false) $this->error = implode('<br/>', $errors);
                $this->applyTemplate('error-class');
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
                if(class_exists('Collective\Html\FormBuilder::class'))
                return Collective\Html\FormBuilder::getValueAttribute($name);
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
        return $this->renderer()->renderArray($options, 'input') . $this->addAssets();
    }


    protected function getTemplate($tag = null)
    {
        $template = parent::getTemplate($tag);
        if(!is_null($template)) return $template;
        return $this->context->getTemplate('input');
    }

    protected function getLabelHTML()
    {
        if(!$this->id) $this->requireID();

        $label = $this->label;
        if(is_array($label)) {
            $label = $this->createElement($label, 'label');    
        } else {
            $label = $this->createElement(
                ['label', 'text' => $label, 'for' => $this->id]);    
        }
        return $label->renderElement();
    }

    protected function getHelpHTML()
    {   
        $help = $this->help;
        if(is_array($help)) {
            $help = $this->createElement($help);    
        } else {
            $help = $this->createElement(
                ['input-help', 'text' => $help]);    
        }
        return $help->renderElement();
    }

    protected function createCol()
    {
        if(is_array($this->col)) {
            return $this->createElement($this->col, 'col');
        } 
        return $this->createElement(['col', 'col' => $this->col]);
    }

    public function renderElement()
    {
        if(!$this->hidden) {
            $html = '';
            if($this->labelFirst && $this->label) $html .= $this->getLabelHTML();
            $html .= parent::renderElement();
            if(!$this->labelFirst && $this->label) $html .= $this->getLabelHTML();
            if($this->help) $html .= $this->getHelpHTML();
            
            if ($this->col === false) return $html;
            $col = $this->createCol();
            if($this->wire_ignore) $col->setAttribute('wire:ignore', '');
            return $this->renderer()->renderElement($col, $html);
        }
    }

    public function isVertical()
    {
        return false;
    }
}
