<?php

namespace dimonka2\flatform\Form\Contracts;

interface IElement
{
    public function renderElement();
    public function render();
    public function getOptions(array $keys);
    public function getTag();
    public function setParent(IElement $item);
    public function getParent(): IElement;
    public function hasParent();
    public function getAttribute($name);
    public function setAttribute($name, $value);
    public function addClass($class);
    public function addStyle($style);
    public function getContext(): IContext;
    public function getText();
    public function setText($text);

    public function setOnRender(?callable $onRender);
    public function getOnRender(): ?callable;
}
