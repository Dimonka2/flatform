<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IContext;

interface IElement
{
    public function renderElement();
    public function getOptions(array $keys);
    public function getTag();
}
