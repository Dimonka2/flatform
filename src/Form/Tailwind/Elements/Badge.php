<?php

namespace dimonka2\flatform\Form\Tailwind\Elements;

use dimonka2\flatform\Form\ElementContainer;

class Badge extends ElementContainer
{
    protected $color = 'blue-500';
    protected $font_color = 'white';
    protected $rounded = 'full';
    protected $size = 'xs';

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'rounded', 'size']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        if($this->font_color) $this->addClass("text-" . $this->font_color);
        if($this->color) $this->addClass("bg-" . $this->color);
        if($this->size) $this->addClass('text-' . $this->size);
        if($this->rounded) $this->addClass('rounded-' . $this->rounded);
        $options = parent::getOptions($keys);
        return $options;
    }
     /**
     * Get the value of color
     */
    public function getColor()
    {
        return $this->color ?? $this->defaultColor;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getTag()
    {
        return $this->type == 'badge' ? 'span' : $this->type;
    }

}
