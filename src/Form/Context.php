<?php

namespace dimonka2\flatform\Form;

use Closure;
use Illuminate\Support\MessageBag;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IDataProvider;
use dimonka2\flatform\Traits\LivewireVersion;

class Context implements IContext
{
    use LivewireVersion;
    protected $mapping = [];    // id => element mapping
    protected $next_id = 100;   // id counter in order to avoid repeated ids
    protected static $renderCount; // this is an indicator that there is an ongoing rendering if it is not 0 or null
    private $factory;           // factory object
    private $style_priority;
    private $debug;
    private $form;              // parent form during reading
    private $dataProvider;      // helps to register data elements for data providers
    protected $errors;

    public const VOID_TAGS = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param',
         'source', 'track', 'wbr'];

    public function __construct()
    {
        $this->style_priority = FlatformService::config('flatform.form.style');
        $this->factory = new ElementFactory($this);
        if(app()->has('session')) {
            $this->errors = session('errors') ? session('errors')->getBags()['default'] ?? new MessageBag : null;
        }
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
        if ($this->debug) {
            debug($this);
            debug($elements);
        }
        if(is_object($elements)){
            return $elements->renderElement();
        }

        if(!self::$renderCount) return $this->_internalRender($elements);
        self::$renderCount++;
        try {
            $html = $this->renderView(view('flatform::context')->with('elements', $elements));
            self::$renderCount--;
        } catch (\Throwable $th) {
            self::$renderCount--;
            throw $th;
        }
        return $html;
    }

    public function _internalRender($elements)
    {
        return $this->renderItem($elements);
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
        $tag = strtolower($tag);
        $voidElement = in_array($tag, self::VOID_TAGS);
        $html = '<' . $tag;
        foreach($element as $key => $value) {
            if(is_scalar($value)) $html .= ' ' . $key . '="' . htmlentities($value) . '"';
        }
        if($voidElement) {
            if(!is_null($text)) logger('Warning! Skipping inner HTML text for void element "' . $tag . '": ' . $text);
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
                    FlatformService::addAssets($asset);
                }
            } else FlatformService::addAssets($assets);
        }
        $this->debug = $options['debug'] ?? false || !!array_search('debug', $options);
        return $this;
    }

    public static function ensureType(array $element, $type)
    {
        if(isset($element['type'])){
            if($element['type'] == $type) return $element;
            $element['type'] = $type;
        } else if(isset($element[0]) && !is_array($element[0])){
            if($element[0] == $type) return $element;
            $element['type'] = $type;
        } else {
            $element['type'] = $type;
        }
        return $element;
    }

    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }

    public function getNamedValue($name, $value = null)
    {
        if (is_null($name)) {
            return $value;
        }

        $request = request();
        if($request) $value = $request->input($this->transformKey($name));

        if (! is_null($value)) {
            return $value;
        }

        if($this->form) return $this->form->getModelValue($name);
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
    public function make($type, $definition = []): ?IElement
    {
        $definition = $this::ensureType($definition, $type);
        return $this->createElement($definition);
    }

    public function Datatable($defaults = [])
    {
        return $this->make('datatable', $defaults);
    }

    public function Table($defaults = [])
    {
        return $this->make('xtable', $defaults);
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

    public function getError($name)
    {
        if(!$name) return null;
        if(!$this->errors) return null;

        return $this->errors->get($name);
    }

    /**
     * Set the value of errors
     *
     * @return  self
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get the value of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get Null Last order Function depending on database driver type
     */

    public function getNullLastOrderFunction($query): Closure
    {
        $driver = $query->getConnection()->getDriverName();
        switch ($driver) {
            case 'mysql':
                return function($column, $direction) use ($query) {
                    return $query->orderByRaw("ISNULL(`$column`), `$column` $direction");
                };
            case 'postgersql':
                return function($column, $direction) use ($query) {
                    return $query->orderByRaw($column . ' ' . $direction . ' NULLS LAST');
                };
            default: // no specific function identified..
                return function($column, $direction) use ($query) {
                    return $query->orderBy($column, $direction);
                };
        }
    }
}
