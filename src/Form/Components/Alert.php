<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

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
        return (is_object($this->title)) ? $this->title->renderElement() : $this->title;
    }

    public function hasText()
    {
        return !is_null($this->text);
    }

    public function getText()
    {
        return (is_object($this->text)) ? $this->text->renderElement() : $this->text;
    }


    protected function createContainer($items)
    {
        $div = $this->createElement([]);
        $div->readItems($items);
        return $div;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['icon', 'close', 'title', 'color']);
        parent::read($element);

        if(is_array($this->title)) {
            $this->title = $this->createContainer($this->title);
        }

        if(is_array($this->text)) {
            $this->text = $this->createContainer($this->text);
        }
    }

    protected function render()
    {
        $aroundHTML = $this->renderItems();
    }
}
