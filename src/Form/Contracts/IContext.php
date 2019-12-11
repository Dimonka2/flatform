<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IElement;

interface IContext
{
    public function renderElement(IElement $element, $aroundHTML = null);
    public function createElement(array $element);
    public function getID($name);
    public function getTemplate($tag);
    public function setTemplatable(IElement $element);
    public function getTemplatable();
}
