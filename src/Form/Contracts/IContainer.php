<?php

namespace dimonka2\flatform\Form\Contracts;

interface IContainer extends IElement
{
    public function renderItems();
    public function readItems(array $items);
    public function getContainer();
    public function setContainer($container);
}
