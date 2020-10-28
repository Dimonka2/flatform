<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IElement;

interface IRenderer
{
    public function renderElement(IElement $element, $aroundHTML = null);
    public function renderView($view, ?string $toStack = null);
    public static function renderArray(array $element, $tag, $text = null);
    public function renderItem($item);
}
