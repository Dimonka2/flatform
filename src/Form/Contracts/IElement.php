<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IContext;

interface IElement
{
    public function render(IContext $context);
    public function getOptions(array $keys);
    public function getTag();
    public function getText();
    public function getSurround();
    public function getHidden();
}
