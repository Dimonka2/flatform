<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class DropdownItem extends Link
{

    protected function read(array $element)
    {
        $this->readSettings($element, ['icon']);
        parent::read($element);
    }

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

    public function renderElement()
    {
        if(!$this->hidden) {
            if($this->is_post()) {
                return $this->renderForm();
            } else  {
                return $this->renderLink();
            }
        }
    }

}
