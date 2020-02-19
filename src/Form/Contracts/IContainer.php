<?php

namespace dimonka2\flatform\Form\Contracts;

interface IContainer
{
    public function renderItems();
    public function readItems(array $items);
}
