<?php

namespace dimonka2\flatform\Form;

use Illuminate\Support\Fluent;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Traits\SettingReaderTrait;

class Element implements IElement
{
    protected const assets = false;

    public $id;
    public $class;
    public $style;
    protected $onRender = null; // closure for rendering

    protected $context;
    protected $type;
    protected $hidden;
    protected $attributes;
    protected $text;
    protected $template;
    protected $parent;
    protected $tooltip; // hints via "title" attribute
    protected $_data;   // data field in datatable

    use SettingReaderTrait;

    protected function read(array $element)
    {
        $onLoaded = $this->readSingleSetting($element, 'onLoaded');
        $this->onRender = $this->readSingleSetting($element, 'onRender');

        $this->readSettings($element, [
            'text',
            'style',
            'class',
            'id',
            'type',
            'tooltip',
            '_data',
            'hidden'=> ['exclude', 'hidden', 'hide'],
            'template',
        ]);
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;

        $this->processAttributes($element);
        if(is_callable($onLoaded)) call_user_func_array($onLoaded, [$this, $element]);
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
    protected function createElement($element, $type = null)
    {
        if($type) $element = $this->context::ensureType($element, $type);
        $element = ElementFactory::preprocessElement($element);
        return $this->context->createElement($element);
    }

    public function __construct(array $element = [], ?IContext $context = null)
    {
        $this->attributes = new Fluent();
        $this->context = $context ?? Flatform::context();
        $this->read($element);
        // add debug logging to any specific element
        if($this->attributes['debug']) debug($this);
    }

    public function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach(array_merge($keys, ['id', 'class', 'style']) as $key){
            if(isset($this->$key) && !is_null($this->$key)) $options[$key] = $this->$key;
        }
        // add tooltip
        if($this->tooltip) {
            if(is_array($this->tooltip)) {

            } else {
                $options['title'] = $this->tooltip;
                $options['data-toggle'] = "tooltip";
                $options['data-placement'] = "top";
            }
        }
        return $options->toArray();
    }

    public function getAttribute($name)
    {
        return $this->attributes[$name];
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);
        return $this;
    }

    protected function processFunction($function, $value)
    {
        switch ($function[1]) {
            case 'time':
                return $value->diffForHumans();
            case 'date':
                return $value->format($function[2] ?? 'd.m.Y');
            case 'route':
                return route($function[2], $value);

        }
    }

    protected function processData()
    {
        if(!is_array($this->_data)) return;
        $provider = $this->context->getDataProvider();
        if($provider){
            // data query format:
            // field_name => attribute name or function($element, $data)
            foreach ($this->_data as $field => $attribute) {
                $value = $provider->getData($field);
                if(is_callable($attribute)){
                    $attribute($this, $value);
                } else{
                    $function = explode(':', $attribute);
                    if(count($function) > 1) {
                        $attribute = $function[0];
                        $value = $this->processFunction($function, $value);
                    }
                    if(property_exists($this, $attribute)) {
                        $this->{$attribute} = $value;
                    } else {
                        $this->setAttribute($attribute, $value);
                    }
                }
            }
        }
    }

    public function renderElement()
    {
        if(!$this->hidden) {
            // process data source
            if($this->_data) $this->processData();
            if(is_callable($this->onRender)) {
                $closure = $this->onRender;
                return $closure($this, $this->context);
            }
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

    public function render()
    {
        // special case
        if($this->type == '_text') return $this->text;
        return $this->context->renderElement($this);
    }

    public function getTag()
    {
        return ($this->type ?? Flatform::config('flatform.form.default-type', 'div') );
    }

    protected function requireID()
    {
        if(is_null($this->id)) {
            $this->id = $this->context->getID($this->name ?? 'id');
            $this->context->setMapping($this->id, $this);
        }
        return $this;
    }

    protected function addAssets()
    {
        if( static::assets ?? false ) return Flatform::addAssets(static::assets);
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

    /**
     * Get the value of context
     */
    public function getContext(): IContext
    {
        return $this->context;
    }

    public function getMapping($id): IElement
    {
        return $this->context->getMapping($id);
    }

    /**
     * Get the value of onRender
     */
    public function getOnRender(): ?callable
    {
        return $this->onRender;
    }

    /**
     * Set the value of onRender
     *
     * @return  self
     */
    public function setOnRender(?callable $onRender)
    {
        $this->onRender = $onRender;

        return $this;
    }
}
