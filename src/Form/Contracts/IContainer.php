<?php

namespace dimonka2\flatform\Form\Contracts;

interface IContainer
{
    public function renderItems($context);
    public function items();

}
