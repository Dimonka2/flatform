<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\Element;

class Image extends Element
{
    protected $src;
    protected function read(array $element)
    {
        $this->readSettings($element, ['src']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        return parent::getOptions(array_merge($keys, ['src']));
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
