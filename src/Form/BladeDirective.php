<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;

class BladeDirective extends Element
{
    public $name;
    public $with;

    public function read(array $element)
    {
        $this->readSettings($element, ['name', 'with']);
        parent::read($element);
    }

    protected function render()
    {
        return $this->context->renderView(
            view('flatform::directive')
            ->with('element', $this)
        );
    }
}
