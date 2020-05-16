<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Flatform;
use Illuminate\Support\MessageBag;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IDataProvider;

class Context implements IContext
{
    protected $elements = [];   // list of elements in context
    protected $mapping = [];    // id => element mapping
    protected $next_id = 100;   // id counter in order to avoid repeated ids
    protected static $renderCount; // this is an indicator that there is an ongoing rendering if it is not 0 or null
    private $factory;           // factory object
    private $style_priority;
    private $errors;
    private $debug;
    private $form;              // parent form during reading
    private $dataProvider;      // helps to register data elements for data providers

    public function __construct(array $elements = [])
    {
        $this->style_priority = FlatformService::config('flatform.form.style');
        $this->factory = new ElementFactory($this);
        $this->setElements($elements);
    }

    public function setElements(array $elements)
    {
        $this->elements = new ElementContainer([], $this);
        $this->elements->setContainer(true);
        $this->errors = request()->session()->get('errors', new MessageBag);
        $this->elements->readItems($elements);
        return $this;
    }

    public function add(array $elements = [])
    {
        $this->elements->readItems($elements);
        return $this;
    }

    public function setMapping($id, IElement $element)
    {
        $this->mapping[$id] = $element;
        return $this;
    }

    public function getMapping($id): ?IElement
    {
        return $this->mapping[$id] ?? null;
    }

    public function getID($name)
    {
        preg_match('/[-_A-Za-z0-9]+/', $name, $matches);
        return $matches[0] . '-' . $this->next_id++;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function createElement(array $element): IElement
    {
        $element = $this->factory->createElement($element, $this);
        if($element->id) $this->setMapping($element->id, $element);
        return $element;
    }

    public function createTemplate($template)
    {
        if($template == '') return;
        $template = $this->getTemplate($template);
        if(!is_array($template)) return;
        return $this->createElement($template);
    }

    public function render($elements = null)
    {
        if(is_array($elements)){
            $this->setElements($elements);
        } elseif(is_object($elements)){
            return $elements->renderElement();
        }
        if ($this->debug) debug($this);
        if(!self::$renderCount) return $this->_internalRender();
        self::$renderCount++;
        try {
            $html = $this->renderView(view('flatform::context'));
            self::$renderCount--;
        } catch (\Throwable $th) {
            self::$renderCount--;
            throw $th;
        }
        return $html;
    }

    public function _internalRender()
    {
        return $this->elements->renderItems();
    }

    public function renderView($view, ?string $toStack = null)
    {
        $html = $view->with('context', $this)->render();
        if(!$toStack) return $html;
        $push = $this->createElement(['push', 'name' => $toStack, 'text' => $html]);
        return $push->renderElement();
    }

    public static function renderArray(array $element, $tag, $text = null)
    {
        $html = '<' . $tag;
        foreach($element as $key => $value) {
            if(!is_array($value)) $html .= ' ' . $key . '="' . htmlentities($value) . '"';
        }
        if(is_null($text)) {
            $html .= ' />';
        } else {
            $html .= '>' . $text . '</' . $tag . '>';
        }
        return $html;
    }


    public function renderElement(IElement $element, $aroundHTML = null)
    {
        $tag = $element->getTag();
        $options = $element->getOptions([]);
        return self::renderArray($options, $tag, $aroundHTML);
    }

    public function renderItem($item)
    {
        if(is_array($item)) {
            $div = new ElementContainer([], $this);
            return $div->readItems($item)->renderItems();
        }
        if(is_object($item)) return $item->renderElement();
        return $item;
    }

    public function getTemplate($tag)
    {
        if(is_null($tag)) return null;
        // logger('getTemplate',  [$tag]);
        foreach (explode(',', $this->style_priority) as $path) {
            $template = FlatformService::config('flatform.' . $path . '.' . $tag, null);
            if(!is_null($template)) return $template;
        }
        return null;
    }

    public function setOptions(array $options)
    {
        // debug($options);
        // read possible options from argument
        if(isset($options['style'])) $this->style_priority = $options['style'];
        if(isset($options['assets'])) {
            $assets = $options['assets'];
            if(is_iterable($assets)) {
                foreach ($assets as $asset) {
                    Flatform::addAssets($asset);
                }
            } else Flatform::addAssets($assets);
        }
        $this->debug = $options['debug'] ?? false || !!array_search('debug', $options);
        return $this;
    }

    public static function ensureType(array $element, $type)
    {
        if(!isset($element['type'])){
            $element['type'] = $type;
        }
        return $element;
    }

    public function getJsStack()
    {
        return FlatformService::config('flatform.form.js_stack', 'js');
    }

    public function getCssStack()
    {
        return FlatformService::config('flatform.form.css_stack', 'css');
    }


    // create specific elements

    public function Datatable($defaults = [])
    {
        if(!isset($defaults['type'])) $defaults['type'] = 'datatable';
        return $this->createElement($defaults);
    }

    public function getForm(): ?IForm
    {
        return $this->form;
    }

    public function setForm(?IForm $form)
    {
        $this->form = $form;
        return $this;
    }

    public function getDataProvider(): ?IDataProvider
    {
        return $this->dataProvider;
    }

    public function setDataProvider(?IDataProvider $provider)
    {
        $this->dataProvider = $provider;
        return $this;
    }


}
