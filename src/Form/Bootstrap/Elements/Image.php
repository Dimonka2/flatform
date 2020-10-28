<?php

namespace dimonka2\flatform\Form\Bootstrap\Elements;

use dimonka2\flatform\Form\Element;

class Image extends Element
{
    protected $src;
    protected $defaultOptions = ['id', 'class', 'style', 'src'];

    protected function read(array $element)
    {
        $this->readSettings($element, ['src']);
        parent::read($element);
    }

    /**
     * Get the value of src
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set the value of src
     *
     * @return  self
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }
}
