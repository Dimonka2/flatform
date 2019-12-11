<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class Element implements IElement
{
    public const template_prefix = "_";
    protected const element_attributes = [
        'id', 'class', 'style',
    ];

    public $id;
    public $class;
    public $style;

    protected $context;
    protected $type;
    protected $hidden;
    protected $attributes = [];
    protected $text;
    protected $template;

    protected function hash()
    {
        return substr(spl_object_hash($this), 11, 5);
    }

    protected function readSettings(array &$element, array $keys)
    {
        foreach($keys as $key){
            if (isset($element[$key])) {
                $value = $element[$key];
                unset($element[$key]);
                $this->$key = $value;
            }
        }
    }

    public function processTemplate()
    {
        // echo "process templates: " . var_dump($this);
        // read properties
        $template = $this->getTemplate();
        // echo "processing template: " . var_dump($template);
        if (is_array($template)) {

            foreach($template as $attribute => $value) {
                switch ($attribute) {
                    case 'template':
                        $this->template = $value;
                        break;
                    case 'id':
                        $this->id = $value;
                        break;
                    case 'type':
                        $this->type = $value;
                        break;
                    case 'class':
                        $this->class = $value;
                        break;
                    case 'style':
                        $this->style = $value;
                        break;
                    case '+class':
                        $this->addClass($value);

                        break;
                    case '+style':
                        $this->addStyle($value);
                        break;
                }
            }
        }

    }

    public function addClass($class)
    {
        $this->class .= ' ' . $class;
    }

    public function addStyle($style)
    {
        $this->style .= ' ' . $style;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, explode(',', 'text,style,class,id,type,hidden'));
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        $this->processTemplate();
    }

    protected function getTemplate()
    {
        return $this->context->getTemplate($this->type);
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

    public function renderElement()
    {
        if(!$this->hidden) {
            $html = $this->render();
            $template = $this->template;
            if($template != "") return view($template)
                ->with('element', $this)
                ->with('html', $html)->render();
            return $html;
        }
    }

    protected function render()
    {
        if($this->hidden) return;
        // special case
        if($this->type == '_text') return $this->text;
        return $this->context->renderElement($this);
    }

    public function getTag()
    {
        return $this->type;
    }


}
