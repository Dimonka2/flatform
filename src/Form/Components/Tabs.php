<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Tabs extends ElementContainer
{
    public function readItems(array $items)
    {
        foreach ($items as $item) {
            $tab = $this->context->createTemplate('tab-item');
            $tab->read($item);
            $tab->requireID();
            $this->elements->push($tab);
        }
    }

    public function renderElement()
    {
        if($this->hidden) return;
        $html = '';
        $template = $this->template;
        if($template != "") $html .= view($template)
            ->with('element', $this)
            ->with('html', $html)->render();
        
        $template = $this->context->getTemplate('tab-content');
        if(is_array($template) && isset($template['template'])){
            $html .= view($template['template'])
                ->with('element', $this)
                ->with('html', $html)->render();
        }
        return $this->context->renderElement($this, $html);
    }

}