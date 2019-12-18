<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;

class Tabs extends ElementContainer
{
    public $pills = false;
    public $justified = false;

    public function readItems(array $items)
    {
        foreach ($items as $item) {
            $tab = $this->createTemplate('tab-item');
            $tab->read($item);
            $tab->items_in_title = false;            
            $tab->requireID();
            $this->elements->push($tab);
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['pills', 'justified']);
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
