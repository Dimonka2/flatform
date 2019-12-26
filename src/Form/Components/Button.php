<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Button extends Link
{
    protected $color;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        $options['class'] = ($options['class'] ?? 'btn') .  ' btn-' . ($this->color ?? 'primary');
        if($this->type == 'submit') $options['type'] = 'submit';
        return $options;
    }

    public function getTag()
    {
        if($this->type == 'submit') return 'button';
        return parent::getTag();
    }
}
