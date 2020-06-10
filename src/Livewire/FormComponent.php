<?php

namespace dimonka2\flatform\Livewire;

use Closure;
use Livewire\Component;
use dimonka2\flatform\Flatform;

class FormComponent extends Component
{
    public $data;

    protected function getForm()
    {
        return null;
    }

    public function render()
    {
        return Flatform::render($this->getForm());
    }

}
