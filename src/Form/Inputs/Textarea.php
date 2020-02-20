<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Textarea extends Input
{
    protected function render()
    {
        $options = $this->getOptions([]);
        $text = $this->needValue();
        return $this->context->renderArray($options, 'textarea', $text) . $this->addAssets();
    }
}
