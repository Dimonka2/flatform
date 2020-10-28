<?php

namespace dimonka2\flatform\Form\Bootstrap\Elements;

use dimonka2\flatform\Form\ElementContainer;

class Badge extends ElementContainer
{
    protected $defaultColor = 'primary';
    protected $color;
    protected $pill;
    protected $size;
    protected $inline;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'pill', 'inline', 'size']);
        parent::read($element);
    }

     /**
     * Get the value of color
     */
    public function getColor()
    {
        return $this->color ?? $this->defaultColor;
    }

    /**
     * Get the value of pill
     */
    public function getPill()
    {
        return $this->pill;
    }

    public function getInline()
    {
        return $this->inline;
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
