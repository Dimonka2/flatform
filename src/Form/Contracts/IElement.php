<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IContext;

interface IElement
{
    public function renderElement(IContext $context, $aroundHTML);
    public function getOptions(array $keys);
    public function getTag();
    public function getText();
}
