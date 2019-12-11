<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IContext;

interface IContainer
{
    public function renderItems();
    public function items();

}
