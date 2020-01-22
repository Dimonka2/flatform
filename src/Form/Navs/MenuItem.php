<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Navs\Menu;
use dimonka2\flatform\Form\Contracts\IContext;

class MenuItem extends Link
{
    protected $badge;
    protected $menu;
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
        $this->readSettings($element, ['badge', 'active']);
        parent::read($element);
    }

    public function offsetSet($offset, $item) {
        if($item instanceof MenuItem) $item->setMenu($this->menu);
        parent::offsetSet($offset, $item);
    }

}
