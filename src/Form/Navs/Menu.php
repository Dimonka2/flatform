<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\ElementContainer;

class Menu extends ElementContainer
{
    // default menu settings
    protected $badgeColor;
    protected $badgePill;

    protected function read(array $element)
    {
        $this->readSettings($element, ['badgeColor', 'badgePill']);
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

    /**
     * Get the value of badgeColor
     */
    public function getBadgeColor()
    {
        return $this->badgeColor;
    }

    /**
     * Get the value of badgePill
     */
    public function getBadgePill()
    {
        return $this->badgePill;
    }
}
