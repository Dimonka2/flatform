<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Widget extends ElementContainer
{
    protected $header;
    protected $body;
    protected $items; // works like a body
    protected $footer;
    protected $tools;



    protected function createSections()
    {
        if(is_array($this->header)) {
            $this->header['type'] = 'widget-header';
            $this->header = $this->createElement($this->header);
        }
        if(is_array($this->tools)) {
            $this->tools['type'] = 'widget-tools';
            $this->tools = $this->createElement($this->tools);
        }
        if(is_array($this->body)) {
            $this->body['type'] = 'widget-body';
            $this->body = $this->createElement($this->body);
        }
        if(is_array($this->items)) {
            $this->items['type'] = 'widget-body';
            $this->items = $this->createElement($this->body);
        }
        if(is_array($this->footer)) {
            $this->footer['type'] = 'widget-footer';
            $this->footer = $this->createElement($this->footer);
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['header', 'body', 'footer', 'tools', 'items']);
        parent::read($element);
        $this->createSections();
    }



}
