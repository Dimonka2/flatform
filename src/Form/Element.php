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
    protected $attributes = [];
    protected $text;
    protected $template;
    protected $parent;


    protected static function readSingleSetting(array &$element, $key)
    {
        if (isset($element[$key])) {
            $value = $element[$key];
            unset($element[$key]);
            return $value;
        } else {
            $key = str_replace('-', '_', trim($key));
            if (isset($element[$key])) {
                $value = $element[$key];
                unset($element[$key]);
                return $value;
            }
        }
        return null;
    }

    protected function readSettings(array &$element, array $keys)
    {
        foreach($keys as $keyKey => $key){
            // allow to map many possible attributes to one in order to support depricated ones
            if(is_array($key)) {
                foreach ($key as $key2) {
                    $value = self::readSingleSetting($element, trim($key2));
                    $keyKey = str_replace('-', '_', trim($keyKey));
                    if ($value !== null) $this->$keyKey = $value;
                }
            } else {
                $value = self::readSingleSetting($element, trim($key));
                $key = str_replace('-', '_', trim($key));
                if ($value !== null) $this->$key = $value;
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
            'hidden'=> ['exclude', 'hidden', 'hide'],
            'template',
        ]);
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;

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

    public function ensureType(array $element, $type)
    {
        return $this->context::ensureType($element, $type);
    }

    public function __construct(array $element, IContext $context)
    {
        $this->context = $context;
        $this->read($element);
        // add debug logging to any specific element
        if($this->attributes['debug'] ?? false) debug($this);
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

    /**
     * Get the value of hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set the value of hidden
     *
     * @return  self
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}
