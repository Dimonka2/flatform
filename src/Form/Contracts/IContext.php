<?php

namespace dimonka2\flatform\Form\Contracts;

use dimonka2\flatform\Form\Contracts\IElement;

interface IContext
{
    public function getRenderer(): IRenderer;
    public function createTemplate($template);
    public static function ensureType(array $element, $type);

    public function createElement(array $element): IElement;
    public function getID($name);
    public function getTemplate($tag);
    public function setOptions(array $options);
    public function getError($name);

    public function getDebug();
    public function setDebug($debug);

    public function setMapping($id, IElement $element);
    public function getMapping($id): ?IElement;

    public function getForm(): ?IForm;
    public function setForm(?IForm $form);

    public function getDataProvider(): ?IDataProvider;
    public function setDataProvider(?IDataProvider $provider);
}
