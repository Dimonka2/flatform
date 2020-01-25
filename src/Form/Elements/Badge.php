<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\ElementContainer;

class Badge extends ElementContainer
{
    protected $defaultColor = 'primary';
    protected $color;
    protected $pill;
    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'pill']);
        parent::read($element);
    }

     /**
     * Get the value of badge_color
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

    public function getTag()
    {
        return $this->type == 'badge' ? 'span' : $this->type;
    }
}
