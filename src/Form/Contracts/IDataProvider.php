<?php

namespace dimonka2\flatform\Form\Contracts;

interface IDataProvider extends IElement
{
    public function registerDataElement(IData $element);
}
