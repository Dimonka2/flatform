<?php

namespace dimonka2\platform\Form\Contracts;

interface IContainer
{
    public function renderItems($context);
    public function items();

}