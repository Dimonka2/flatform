<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Flatform;
use Illuminate\Support\MessageBag;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Traits\LivewireVersion;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IRenderer;
use dimonka2\flatform\Form\Contracts\IDataProvider;

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

    public function __construct()
    {
        $this->style_priority = FlatformService::config('flatform.form.style');
        $this->factory = new ElementFactory($this, strpos($this->style_priority, 'tailwind') !== false);
        if(app()->has('session')) {
            $this->errors = session('errors') ? session('errors')->getBags()['default'] ?? new MessageBag : null;
        }
        debug($this);
    }

    protected function setStyle($style)
    {
        $this->style_priority = $style;
        $this->factory->setTailwind(strpos($this->style_priority, 'tailwind') !== false);
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
        if(isset($options['style'])) $this->setStyle($options['style']);

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
     * Get the value of debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set the value of debug
     *
     * @return  self
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    public function getRenderer(): IRenderer
    {
        return Flatform::renderer();
    }
}
