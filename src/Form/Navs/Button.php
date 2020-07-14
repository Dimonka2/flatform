<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\Link;

class Button extends Link
{
    protected $color;
    protected $size;
    protected $toggle;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'size', 'toggle']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        $options['class'] = ($options['class'] ?? 'btn') .  ' btn-' . ($this->color ?? 'primary') .
            ($this->size ? ' btn-' . $this->size : '').
            ($this->toggle ? ' dropdown-toggle': '');
        if($this->type == 'submit') $options['type'] = 'submit';
        return $options;
    }

    public function getTag()
    {
        if($this->type == 'submit') return 'button';
        return parent::getTag();
    }
}
