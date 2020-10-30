<?php

namespace dimonka2\flatform\Form\Tailwind\Inputs;

use dimonka2\flatform\Form\Tailwind\Inputs\Input;

class Textarea extends Input
{
    public function render()
    {
        $options = $this->getOptions([]);
        $text = $this->needValue();
        if(is_null($text)) $text = null;
        return $this->renderer()->renderArray($options, 'textarea', $text) . $this->addAssets();
    }

}
