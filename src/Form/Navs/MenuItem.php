<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Navs\Menu;
use dimonka2\flatform\Form\Contracts\IElement;

class MenuItem extends Link
{
    protected $badge;
    protected $badgeColor;
    protected $menu;
    protected $open;
    public $active;

    public function getMenu()
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
        return $this;
    }

    public function setParent(IElement $item)
    {
        if($item instanceof Menu) $this->menu = $item;
        return parent::setParent($item);
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['badge', 'active', 'badgeColor']);
        parent::read($element);
        if($this->active) {
            if(is_object($this->parent) and ($this->parent instanceof MenuItem)) {
                $this->parent->setOpen(true);
            }
        }
    }

    /**
     * Get the value of open
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set the value of open
     *
     * @return  self
     */
    public function setOpen($open)
    {
        $this->open = $open;
        if(is_object($this->parent) and ($this->parent instanceof MenuItem)) {
            $this->parent->setOpen(true);
        }
        return $this;
    }

    /**
     * Get the value of badge_color
     */
    public function getBadgeColor()
    {
        return $this->badgeColor;
    }

    /**
     * Get the value of badge
     */
    public function getBadge()
    {
        return $this->badge;
    }
}
