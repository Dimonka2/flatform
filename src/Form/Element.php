<?php

namespace dimonka2\flatform\Form;

use Illuminate\Support\Fluent;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IRenderer;
use dimonka2\flatform\Traits\SettingReaderTrait;

class Element implements IElement
{
    use SettingReaderTrait;

    protected const assets = false;
    protected $defaultOptions;

    public $id;
    public $class;
    public $style;
    protected $onRender = null; // closure for rendering

    protected $context; // context
    protected $type;    // element tag and/or flatform template
    protected $hidden;
    protected $attributes;
    protected $text;     // simple text inside this element
    protected $template; // blade template
    protected $parent;  // parent element
    protected $tooltip; // hints via "title" attribute
    protected $_data;   // data field in datatable


    protected function renderer(): IRenderer
    {
        return $this->context->getRenderer();
    }

    protected function read(array $element)
    {

        $this->onRender = $this->readSingleSetting($element, 'onRender');
        $attributes = $this->readSingleSetting($element, '_attributes');
        if(is_array($attributes)) $this->processAttributes($attributes);

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
        $this->class .= ' ' . $class;
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

    public function renderItem($item)
    {
        if(is_array($item)) return $this->createContainer($item)->renderItems();
        if(is_object($item)) return $item->renderElement();
        return $item;
    }

    protected function createContainer($items): ElementContainer
    {
        $div = new ElementContainer([], $this->context);
        $div->readItems($items);
        return $div;
    }

    public function __construct(array $element = [], ?IContext $context = null)
    {
        $this->attributes = new Fluent();
        $this->context = $context ?? FlatformService::context();
        $onLoaded = $this->readSingleSetting($element, 'onLoaded');
        $this->read($element);
        if(is_callable($onLoaded)) call_user_func_array($onLoaded, [$this, $element]);
        if(!is_null($this->id)) $this->context->setMapping($this->id, $this);
        // add debug logging to any specific element
        if($this->attributes['debug']) debug($this);
    }

    protected function getDefaultOptions(): array
    {
        return $this->defaultOptions ?? ['id', 'class', 'style'];
    }

    public function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach(array_merge($keys, $this->getDefaultOptions()) as $key){
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
        $provider = $this->context->getDataProvider();
        if($provider){
            $data = $this->_data;
            if(!is_array($data)) $data = [$data];
            // data query format:
            // field_name => attribute name or function($element, $data)
            foreach ($data as $field => $attribute) {
                $value = $provider->getData($field);
                if($attribute instanceof \Closure){
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
                return ($this->onRender)($this);
            }
            $html = $this->render();
            $template = $this->template;
            if(!is_null($template) &&  $template != false) {
                foreach(explode(';', $template) as $template) {
                    $html = $this->renderer()->renderView(
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
        return $this->context->getRenderer()->renderElement($this, $this->text);
    }

    public function getTag()
    {
        return ($this->type ?? FlatformService::config('flatform.form.default-type', 'div') );
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
        if( static::assets ?? false ) return FlatformService::addAssets(static::assets);
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

    /**
     * Set the value of defaultOptions
     *
     * @return  self
     */
    public function setDefaultOptions($defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;

        return $this;
    }
}
