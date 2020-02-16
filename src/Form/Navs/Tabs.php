<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\ElementFactory;

class Tabs extends ElementContainer
{
    public $pills = false;
    public $justified = false;
    public $activeID;

    public function readItems(array $items)
    {
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
        $this->readSettings($element, ['pills', 'justified', 'activeID']);
        parent::read($element);
    }


    public function renderElement()
    {
        if($this->hidden) return;
        $html = '';
        $template = $this->template;
        if($template != "") $html .= $this->context->renderView(
            view($template)
            ->with('element', $this)
            ->with('html', $html)
        );

        $template = $this->context->getTemplate('tab-content');
        if(is_array($template) && isset($template['template'])){
            $html .= $this->context->renderView(view($template['template'])
                ->with('element', $this)
                ->with('html', $html)
            );
        }
        return $this->context->renderElement($this, $html);
    }

}
