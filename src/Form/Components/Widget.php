<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;

class Widget extends ElementContainer
{
    public $icon;
    protected $title;
    protected $body;
    protected $items; // works like an alternative to body
    protected $footer;
    protected $tools;
    public $body_class;

    public function hasTitle()
    {
        return !is_null($this->title);
    }

    public function getTitle()
    {
        return $this->renderItem($this->title);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function renderTools()
    {
        return $this->tools->renderItems();
    }

    public function renderBody()
    {
        return $this->body->renderItems();
    }

    public function renderFooter()
    {
        return $this->footer->renderItems();
    }

    public function hasIcon()
    {
        return !is_null($this->icon);
    }

    public function hasTools()
    {
        return !is_null($this->tools);
    }

    public function hasHeader()
    {
        return $this->hasTitle() || $this->hasIcon() || $this->hasTools();
    }

    public function hasBody()
    {
        return !is_null($this->body);
    }

    public function hasFooter()
    {
        return !is_null($this->footer);
    }

    public function render()
    {

        return "";
    }

    protected function createSections()
    {
        if(is_array($this->tools)) {
            $this->tools = $this->createContainer($this->tools);
        }
        if(is_array($this->body)) {
            $this->body = $this->createContainer($this->body);
        } elseif(is_array($this->items)) {
            $this->body = $this->createContainer($this->items);
        }
        if(is_array($this->footer)) {
            $this->footer = $this->createContainer($this->footer);
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['title', 'icon', 'body', 'footer', 'tools', 'items', 'body_class']);
        parent::read($element);
        $this->createSections();

    }

}
