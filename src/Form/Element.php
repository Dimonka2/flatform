<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class Element implements IElement
{
    protected const element_attributes = [
        'id', 'class', 'style'
    ];

    public $id;
    public $class;
    public $style;
    public $onRender = null; // closure for rendering

    protected $context;
    protected $type;
    protected $hidden;
    protected $exclude; // depricated
    protected $attributes = [];
    protected $text;
    protected $template;
    protected $parent;


    protected function readSettings(array &$element, array $keys)
    {
        foreach($keys as $key){
            $key = trim($key);
            if (isset($element[$key])) {
                $value = $element[$key];
                unset($element[$key]);
                $key = str_replace('-', '_', $key);
                $this->$key = $value;
            }
        }
    }

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'text',
            'style',
            'class',
            'id',
            'type',
            'hidden',
            'exclude',
            'template',
        ]);
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        if(!is_null($this->exclude)) $this->hidden = !!$this->exclude;
        $this->processAttributes($element);
        if(!is_null($this->id)) $this->context->setMapping($this->id, $this);
        return $this;
    }

    public function processAttributes($element)
    {

        foreach($element as $attribute => $value) {
            switch ($attribute) {
                case '+class':
                    $this->addClass($value);
                    break;
                case '+style':
                    $this->addStyle($value);
                    break;
                default:
                    $this->attributes[$attribute] = $value;
            }
        }
    }

    public function addClass($class)
    {
        $this->class = ($this->class ?? '') . ' ' . $class;
        return $this;
    }

    public function addStyle($style)
    {
        $this->style = ($this->style ?? '') . ' ' . $style;
        return $this;
    }

    protected function getTemplate($tag = null)
    {
        return $this->context->getTemplate($tag ?? $this->type);
    }

    protected function createTemplate($template)
    {
        return $this->context->createTemplate($template);
    }
    protected function createElement($element)
    {
        return $this->context->createElement($element);
    }

    public function __construct(array $element, IContext $context)
    {
        $this->context = $context;
        $this->read($element);
    }

    public function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach(array_merge($keys, self::element_attributes) as $key){
            if(isset($this->$key) && !is_null($this->$key)) $options[$key] = $this->$key;
        }
        return $options;
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function renderElement()
    {
        if(!$this->hidden) {
            $html = $this->render();
            $template = $this->template;
            if(!is_null($template) &&  $template != false) {
                foreach(explode(';', $template) as $template) {
                    $html = $this->context->renderView(
                        view($template)
                        ->with('element', $this)
                        ->with('html', $html)
                    );
                }

            }
            return $html;
        }
    }

    protected function render()
    {
        if($this->hidden) return;
        if(is_callable($this->onRender)) {
            $closure = $this->onRender;
            return $closure($this, $this->context);
        }
        // special case
        if($this->type == '_text') return $this->text;
        return $this->context->renderElement($this);
    }

    public function getTag()
    {
        return ($this->type ?? config('flatform.form.default-type', 'div') );
    }

    public function getHidden()
    {
        return $this->hidden;
    }

    protected function requireID()
    {
        if(is_null($this->id)) {
            $this->id = $this->context->getID($this->name ?? 'id');
            $this->context->setMapping($this->id, $this);
        }
        return $this;
    }

    public function getParent(): IElement
    {
        return $this->parent;
    }

    public function setParent(IElement $item)
    {
        $this->parent = $item;
        return $this;
    }

    public function hasParent()
    {
        return $this->parent !== null;
    }
}
