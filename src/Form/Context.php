<?php

namespace dimonka2\flatform\Form;

use \ReflectionClass;

use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Illuminate\Support\MessageBag;

class Context implements IContext
{
    protected $elements = [];   // list of elements in context
    protected $mapping = [];    // id => element mapping
    protected $next_id = 100;   // id counter in order to avoid repeated ids
    private $factory;           // factory object
    private $cofig_template_path;
    private $errors;
    private $debug;

    public function __construct(array $elements = [])
    {
        $this->cofig_template_path = config('flatform.form.style');
        $this->factory = new ElementFactory($this);
        $this->errors = request()->session()->get('errors', new MessageBag);
        $this->elements = new ElementContainer([], $this);
        $this->elements->readItems($elements);
    }

    public function add(array $elements = [])
    {
        $this->elements->readItems($elements);
        return $this;
    }

    public function setMapping($id, IElement $element)
    {
        $mapping[$id] = $element;
        return $this;
    }

    public function getMapping($id): IElement
    {
        return $mapping[$id] ?? null;
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
        return $this->factory->createElement($element, $this);
    }

    public function createTemplate($template)
    {
        if($template == '') return;
        $template = $this->getTemplate($template);
        if(!is_array($template)) return;
        return $this->createElement($template);
    }

    public function render()
    {
        if ($this->debug) debug($this);
        return $this->elements->renderItems($this);
    }

    public function renderView($view)
    {
        return $view->with('context', $this)->render();
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

    public function getTemplate($tag)
    {
        if(is_null($tag)) return null;
        // logger('getTemplate',  [$tag]);
        foreach (explode(',', $this->cofig_template_path) as $path) {
            $template = config('flatform.' . $path . '.' . $tag, null);
            if(!is_null($template)) return $template;
        }
        return null;
    }

    public function setOptions($options)
    {
        // read possible options from argument
        $this->debug = $options['debug'] ?? false;
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
        return config('flatform.form.js_stack', 'js');
    }

    public function getCssStack()
    {
        return config('flatform.form.css_stack', 'css');
    }


    // create specific elements

    public function Datatable($defaults = [])
    {
        if(!isset($defaults['type'])) $defaults['type'] = 'datatable';
        return $this->createElement($defaults);
    }
}
