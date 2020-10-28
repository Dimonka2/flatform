<?php

namespace dimonka2\flatform\Form\Bootstrap\Navs;

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


    public function renderElement()
    {
        if($this->hidden) return;
        $html = '';
        $template = $this->template;
        if($template != "") $html .= $this->renderer()->renderView(
            view($template)
            ->with('element', $this)
            ->with('html', $html), $this->navsStack);

        $template = $this->context->getTemplate('tab-content');
        if(is_array($template) && isset($template['template'])){
            $html .= $this->renderer()->renderView(view($template['template'])
                ->with('element', $this)
                ->with('html', $html), $this->contentStack
            );
        }
        return $this->renderer()->renderElement($this, $html);
    }

    public function isTab(IElement $tab)
    {
        if($tab->getHidden()) return false;
        $isTab = $tab->getAttribute('tab');
        return $isTab === null || $isTab;
    }

}
