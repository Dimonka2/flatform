<?php

namespace dimonka2\flatform\Form\Tailwind\Navs;

use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\ElementContainer;

class Tabs extends ElementContainer
{
    public $pills = false;
    public $justified = false;
    public $activeID;
    public $contentStack;
    public $navsStack;

    public function readItems(array $items, $reset = false)
    {
        if($reset) $this->elements = [];
        foreach ($items as $item) {
            $tab = $this->createElement($item, 'tab-item');
            $tab->items_in_title = false;
            $tab->requireID();
            $this->elements[] = $tab;
            
            if(!$tab->getHidden() ){
                if (!$this->activeID) {
                    $this->activeID = $tab->id;
                } elseif($tab->getAttribute('active')) {
                    $this->activeID = $tab->id;
                }
            }
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['pills', 'justified', 'activeID', 'contentStack', 'navsStack']);
        parent::read($element);
    }


    protected function renderContent()
    {
        $container = new ElementContainer();
        $container->container = true;
        $nav = $this->createTemplate('tabs-nav');
        $content = $this->createTemplate('tabs-content');

        if(!$this->pills) $nav->addClass('border-b');
        foreach ($this->elements as $tab) {
            if(!$tab->getHidden() ){
                $nav[] = $tab;
                $tab->setParent($this);
                
            }
        }        
        $container[] = $nav; 
        $container[] = $content;
        return $container->renderElement();
    }

    public function render()
    {
        return parent::render();
    }
    

    public function isTab(IElement $tab)
    {
        if($tab->getHidden()) return false;
        $isTab = $tab->getAttribute('tab');
        return $isTab === null || $isTab;
    }

}
