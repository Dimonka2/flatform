<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IElement;

interface IContext
{
    public function renderElement(IElement $element, $aroundHTML = null);
    public function renderView($view);
    public static function renderArray(array $element, $tag, $text = null);

    public function createElement(array $element): IElement;
    public function getID($name);
    public function getTemplate($tag);
    public function setOptions($options);

    public function setMapping($id, IElement $element);
    public function getMapping($id): IElement;
}
