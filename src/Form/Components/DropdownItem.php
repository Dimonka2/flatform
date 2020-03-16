<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;

class DropdownItem extends Link
{

    public function getTitle()
    {
        $html = '';
        if(!is_null($this->icon)) {
            $template = $this->createTemplate('dd-item-icon');
            if(is_object($template)) {
                $template->addClass($this->icon);
                $html .= $template->renderElement();
            }
        }
        if(!is_null($this->title)) {
            $template = $this->createTemplate('dd-item-title');
            if(is_object($template)) {
                $template->addTextElement($this->title);
                $html .= $template->renderElement();
            }
        }
        if(!is_null($this->badge)) {
            $html .= $this->renderBadge();
        }

            return $html;
    }

    protected function renderLink()
    {
        $html = $this->context->renderElement($this, $this->getTitle());
        $template = $this->template;
        if($template != "") return $this->context->renderView(
            view($template)
            ->with('element', $this)
            ->with('html', $html)
        );
        return $html;
    }


}
