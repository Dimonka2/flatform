<?php

namespace dimonka2\flatform\Form\Bootstrap\Navs;

use dimonka2\flatform\Form\Bootstrap\Link;
use dimonka2\flatform\Form\Bootstrap\Navs\Menu;
use dimonka2\flatform\Form\Contracts\IElement;

class MenuItem extends Link
{
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
        if($item instanceof Menu) {
            $this->menu = $item;
        } elseif ($item instanceof MenuItem) {
            $this->menu = $item->getMenu();
        }
        if(is_object($this->menu)) {
            if(is_null($this->badgeColor)) $this->badgeColor = $this->menu->getBadgeColor();
            if(is_null($this->badgePill)) $this->badgePill = $this->menu->getBadgePill();
        }
        $res = parent::setParent($item);
        if($this->active) {
            $this->setOpen(true);
        }
        return $res;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['badge', 'active']);
        parent::read($element);
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
        if(is_object($this->parent) && ($this->parent instanceof MenuItem)) {
            $this->parent->setOpen($open);
        }
        return $this;
    }

}
