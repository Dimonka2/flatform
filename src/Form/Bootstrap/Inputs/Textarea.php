<?php

namespace dimonka2\flatform\Form\Bootstrap\Inputs;

use dimonka2\flatform\Form\Bootstrap\Input;

class Textarea extends Input
{
    public function render()
    {
        $options = $this->getOptions([]);
        $text = $this->needValue();
        if(is_null($text)) $text = '';
        return $this->renderer()->renderArray($options, 'textarea', $text) . $this->addAssets();
    }
}
