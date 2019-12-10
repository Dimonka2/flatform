<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IContext;

class Input extends Element
{

    protected $name;
    protected $label;
    protected $value;
    protected $help;

    protected function read(array $element, IContext $context)
    {
        $fields = 'name,label,value,help';
        $this->readSettings($element, explode(',', $fields));
        parent::read($element, $context);
    }

    protected function requireID(IContext $context)
    {
        if(is_null($this->id)) {
            $this->id = $context->getID($this->name ?? 'id');
        }
    }

    protected function getTemplate(IContext $context)
    {
        $template = parent::getTemplate($context);
        if(!is_null($template)) return $template;
        return $context->getTemplate('input');
    }
}
