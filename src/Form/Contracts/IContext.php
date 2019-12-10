<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IElement;

interface IContext
{
    public function renderElement(IElement $element, IElement $around);
    public function createElement(array $element);
    public function getID($name);
}
