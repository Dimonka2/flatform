<?php

namespace dimonka2\flatform\Form\Components\JsTree;

use dimonka2\flatform\Traits\SettingReaderTrait;

class TreeElement
{
    use SettingReaderTrait;
    protected const properties = ['id', 'icon', 'text', 'disabled', 'opened', 'url'];
    protected $id;
    protected $icon;
    protected $text;
    protected $disabled;
    protected $opened;
    protected $url;

    protected $items = [];
    private $parent;
    private $tree;

    public function __construct(array $element, Tree $tree, ?TreeElement $parent = null)
    {
        $this->parent = $parent;
        $this->tree = $tree;
        $this->read($element);
        $tree->setMapping($this);
    }

    public function readItems(array $items)
    {
        foreach ($items as $item) {
            if(is_array($item)) $this->items[] = new TreeElement($item, $this->tree, $this);
        }
    }

    protected function read(array $element)
    {
        // preprocess elements
        foreach($element as $key => $value) {
            if(is_integer($key)) {
                unset($element[$key]);
                if(is_array($value)) {
                    $element['items'] = $value;
                } else {
                    $element[$value] = true;
                }
            }
        }
        if(isset($element['items'])) $this->readItems($element['items']);

        $this->readSettings($element, static::properties);

        return $this;
    }

    public function getChildrenArray()
    {
        $children = [];
        foreach ($this->items as $item) {
            $children[] = $item->toArray();
        }
        return $children;
    }

    public function count()
    {
        return count($this->items);
    }

    public function toArray()
    {
        $item = [];
        $item['text'] =  $this->tree->renderText($this->text);
        if($this->id) $item['id'] = $this->id;
        if($this->icon) $item['icon'] = $this->icon;
        if(!is_null($this->opened)) $item['state']['open'] = $this->opened;
        if($this->disabled) $item['state']['disabled'] = true;
        if($this->url) $item['data']['url'] = $this->url;
        if(count($this->items) > 0) {
            $item['children'] = $this->getChildrenArray();
        }
        return $item;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }
}
