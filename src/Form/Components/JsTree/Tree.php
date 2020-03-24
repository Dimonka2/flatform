<?php

namespace dimonka2\flatform\Form\Components\JsTree;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\ElementContainer;

class Tree extends ElementContainer
{
    protected const assets = 'jstree';
    protected $root;
    protected $responsive;
    protected $plugins;
    protected $mapping = [];

    protected function read(array $element)
    {
        $this->readSettings($element, ['responsive', 'plugins']);
        if(isset($element['items'])) {
            $this->root->readItems($element['items']);
            unset($element['items']);
        }
        parent::read($element);

        $this->requireID();
        return $this;
    }

    public function __construct(array $element = [], ?IContext $context = null)
    {
        $this->root = new TreeElement([], $this);
        parent::__construct($element, $context);
    }

    public function setMapping(TreeElement $element)
    {
        $this->mapping[$element->getId()] = $element;
    }

    public function render()
    {
        $html = $this->context->renderElement($this, '');
        return $this->context->renderView(
            view(Flatform::config('flatform.assets.jstree.render', 'flatform::jstree'))
                ->with('element', $this)
                ->with('html', $html)
        ) . $this->addAssets();

    }

    public function getElementById($id): ?TreeElement
    {
        return $this->mapping[$id] ?? null;
    }

    public function getTree()
    {
        $tree = [];
        if(!is_null($this->responsive)) $tree['core']['themes']['responsive'] = $this->responsive;
        if($this->plugins) $tree['plugins'] = $this->plugins;
        if($this->root->count()) $tree['core']['data'] = $this->root->getChildrenArray();
        return $tree;
    }

    public function renderText($text)
    {
        return $this->renderItem($text);
    }
}
