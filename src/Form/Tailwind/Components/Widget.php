<?php

namespace dimonka2\flatform\Form\Tailwind\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Tailwind\ShadowTrait;

class Widget extends ElementContainer
{
    use ShadowTrait;

    public $icon;
    protected $title;
    protected $footer;
    protected $tools;
    public $body_class;

    public function hasTitle()
    {
        return !is_null($this->title);
    }

    public function renderTitle()
    {
        return $this->renderIcon() . $this->renderItem($this->title);
    }

    public function renderIcon()
    {
        return $this->renderItem($this->icon);
    }

    public function renderTools()
    {
        return $this->renderItem($this->tools);
    }

    public function renderBody()
    {
        return $this->renderItems() . $this->text;
    }

    public function renderFooter()
    {
        return $this->renderItem($this->footer);
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
        return count($this) || $this->text;
    }

    public function hasFooter()
    {
        return !is_null($this->footer);
    }

    protected function renderContent()
    {
        $container = new ElementContainer();
        $container->container = true;
        if($this->hasHeader()) {
            $header = $this->createTemplate('widget-header');
            $title = $this->createTemplate('widget-header-title');
            if($this->hasTitle()) {
                $title->setText($this->renderTitle());
            }
            $header[] = $title;
            if($this->hasTools()) {
                $tools = $this->createTemplate('widget-header-tools');
                $tools->setText($this->renderTools());
                $header[] = $tools;
            }
            // add header to the element list
            $container[] = $header;
        }
        if($this->hasBody())  {
            $body = $this->createTemplate('widget-body');
            $body->setText($this->renderBody());
            $container[] = $body;
        }

        if($this->hasFooter()) {
            $footer = $this->createTemplate('widget-footer');
            $footer->setText($this->renderFooter());
            $container[] = $footer;
        }           
        debug($container);
        return $container->renderElement();
    }

    protected function createSections()
    {
        if(is_array($this->tools)) {
            $this->tools = $this->createContainer($this->tools);
        }
        if(is_array($this->footer)) {
            $this->footer = $this->createContainer($this->footer);
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['title', 'icon', 'footer', 'tools', 'body_class']);
        $body = $this->readSingleSetting($element, 'body');
        if(is_array($body)){ 
            $this->readItems($body);
        } else {
            $this->setText($body);
        }
        parent::read($element);
        $this->createSections();

    }

}
