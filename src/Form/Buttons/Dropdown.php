<?php

namespace dimonka2\flatform\Form\Buttons;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Dropdown extends ElementContainer
{
    public $toggle;
    public $shadow;
    public $title;
    public $direction;

    protected function read(array $element, IContext $context)
    {
        $this->readSettings($element, ['toggle', 'shadow', 'title', 'direction']);
        parent::read($element, $context);
    }

    protected function render(IContext $context, $aroundHTML)
    {
        return $this->renderItems($context, $aroundHTML);
    }

}
