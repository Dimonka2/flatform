<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Textarea extends Input
{
    public function render()
    {
        $options = $this->getOptions([]);
        $text = $this->needValue();
        if(is_null($text)) $text = '';
        return $this->context->renderArray($options, 'textarea', $text) . $this->addAssets();
    }
}
