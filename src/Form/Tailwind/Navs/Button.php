<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

class Button extends Link
{
    protected $color = 'primary';
    protected $size;
    protected $toggle;

    protected $builtInColors = ['primary', 'secondary', 'success', 'danger'];

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'size', 'toggle']);
        parent::read($element);
    }

    protected function applyColor()
    {
        $color = strtolower($this->color);
        if(in_array($color, $this->builtInColors)) {
            $template = $this->context->getTemplate('button-' . $color);
            if(!is_null($template)) $this->processAttributes($template);
            return;
        }
    }

    protected function addToggle()
    {
        $toggle = $this->createElement(['include', 'name' => 'flatform::icons.chevron-down']);
        $this[] = $toggle;
    }

    public function getOptions(array $keys)
    {
        if($this->toggle) $this->addToggle();
        if($this->color) $this->applyColor();
        $options = parent::getOptions($keys);
        if($this->type == 'submit') $options['type'] = 'submit';
        return $options;
    }

    public function getTag()
    {
        if($this->type == 'submit') return 'button';
        return parent::getTag();
    }
}
