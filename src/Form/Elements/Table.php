<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\ElementContainer;


class Table extends ElementContainer
{
    protected $thead;
    protected $tbody;

    protected function read(array $element)
    {
        $this->readSettings($element, ['thead', 'tbody']);
        parent::read($element);
        if($this->thead){
            if(is_array($this->thead)) {
                $thead = $this->thead;
                $this->thead = $this->createElement(['thead']);
                $this->thead->readItems($thead);
            }
        }
        if($this->tbody){
            if(is_array($this->tbody)) {
                $tbody = $this->tbody;
                $this->tbody = $this->createElement(['tbody']);
                $this->tbody->readItems($tbody);
            }
        }
    }

    public function renderItems()
    {
        $html = '';
        if(is_object($this->thead)) $html .= $this->thead->renderElement();
        if(is_object($this->tbody)) $html .= $this->tbody->renderElement();
        return $html . parent::renderItems();
    }

}
