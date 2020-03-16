<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;

class BladeDirective extends Element
{
    public $name;
    public $with;
    public $items;

    public function read(array $element)
    {
        $this->readSettings($element, ['name', 'with', 'items']);
        if(is_array($this->items)){
            $items  = $this->items;
            $this->items = new ElementContainer([], $this->context);
            $this->items->readItems($items);
        } else $this->items = null;

        parent::read($element);
    }

    public function render()
    {
        if($this->type == 'extends') {
            return $this->context->renderView(
                view('flatform::extends')
                ->with('element', $this)
            );
        }
        // debug(print_r($this, true));
        return $this->context->renderView(
            view('flatform::directive')
            ->with('element', $this)
        );
    }
}
