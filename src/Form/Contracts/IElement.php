<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IContext;

interface IElement
{
    public function renderElement();
    public function getOptions(array $keys);
    public function getTag();
    public function setParent(IElement $item);
    public function getParent(): IElement;
    public function hasParent();
    public function getAttribute($name);
    public function setAttribute($name, $value);
    public function addClass($class);
    public function addStyle($style);
}
