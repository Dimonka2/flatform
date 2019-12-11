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
        $options['class'] .=  ' btn-' . ($this->color ?? 'primary');
        return $options;
    }

    protected function renderLink()
    {
        if($this->type == 'submit') return Form::submit($this->title, $this->getOptions([]));
        return parent::renderLink();
    }
}
