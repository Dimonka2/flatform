<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\ElementContainer;

class Menu extends ElementContainer
{

    protected function read(array $element)
    {
        // $this->readSettings($element, ['badge', 'active']);
        parent::read($element);
        // debug($this);
    }

    public function addMenuItem($definition): MenuItem
    {
        if(!isset($definition['type'])) $definition['type'] = 'menu-item';
        $item = $this->createElement($definition);
        $this[] = $item;
        return $item;
    }

}
