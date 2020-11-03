<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

use dimonka2\flatform\Form\Tailwind\ShadowTrait;

class Button extends Link
{
    use ShadowTrait;
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
            return $this->applyTemplate('button-' . $color);
        }
    }

    protected function renderToggle()
    {
        $this->addClass('gap-4');
        $toggle = $this->createElement(['include', 'name' => 'flatform::icons.chevron-down']);
        return $toggle->render();
    }

    public function getTitle()
    {
        if($this->color) $this->applyColor();
        $html = parent::getTitle();
        if($this->toggle) $html .= $this->renderToggle();
        $html = $this->renderItem([['div', 'class' => 'flex justify-center', 'text' => $html]]);
        return $html;
    }

    public function getOptions(array $keys)
    {
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
