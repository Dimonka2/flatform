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

    protected function getTitle()
    {
        $html = '';
        if(!is_null($this->icon)) {
            $template = $this->context->createTemplate('dd-item-icon');
            if(is_object($template)) {
                $template->addClass($this->icon);
                $html .= $template->renderElement();
            }
        }
        if(!is_null($this->title)) {
            $template = $this->context->createTemplate('dd-item-title');
            if(is_object($template)) {
                $template->addTextElement($this->title);
                $html .= $template->renderElement();
            }
        }
        return $html;
    }

    public function getTag()
    {
        return 'a';
    }
}
