<?php

namespace dimonka2\flatform\Form\Tailwind\Elements;

use dimonka2\flatform\Form\ElementContainer;

class Grid extends ElementContainer
{
    protected $cols = 6;
    protected $gap;

    protected function read(array $element)
    {
        $this->readSettings($element, ['cols', 'gap']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $this->addClass('grid');
        if($this->cols) $this->addClass("grid-cols-" . $this->cols);
        if($this->gap) $this->addClass("gap-" . $this->gap);
        return parent::getOptions($keys);
    }

    public function getTag()
    {
        return $this->type == 'grid' ? 'div' : $this->type;
    }

}