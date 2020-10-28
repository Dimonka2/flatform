<?php

namespace dimonka2\flatform\Form\Bootstrap\Components;

use dimonka2\flatform\Form\ElementContainer;

class Alert extends ElementContainer
{
    public $icon;
    public $close;
    protected $title;
    public $color = 'primary';

    public function hasTitle()
    {
        return !is_null($this->title);
    }

    public function getTitle()
    {
        return $this->renderItem($this->title);
    }

    public function hasText()
    {
        return !is_null($this->text);
    }

    public function getText()
    {
        return $this->renderItem($this->text);
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['icon', 'close', 'title', 'color']);
        parent::read($element);
    }

    public function render()
    {
        return $this->renderItems();
    }
}
