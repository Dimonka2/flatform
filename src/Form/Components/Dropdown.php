<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Link;
use dimonka2\flatform\Form\Contracts\IContext;

class Dropdown extends Link
{
    public $toggle;
    public $shadow;
    public $direction;

    public function __construct(array $element, IContext $context)
    {
        parent::__construct($element, $context);
        $this->items_in_title = false;
    }

    protected function read(array $element)
    {
        $this->items_in_title = false;
        $this->readSettings($element, ['toggle', 'shadow', 'direction']);
        parent::read($element);

    }

    protected function render()
    {
        return $this->renderItems();
    }

}
