<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class Element implements IElement
{
    public const template_prefix = "_";
    private $type;
    protected $hidden;
    public $id;
    public $class;
    public $style;
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

    public function processTemplate(IContext $context)
    {
        // echo "process templates: " . print_r($this->type, true);
        // read properties
        $template = $this->getTemplate($context);
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
                        $this->class .= $value;
                        break;
                    case '+style':
                        $this->style .= $value;
                        break;
                }
            }
        }

    }

    protected function read(array $element, IContext $context)
    {
        $this->readSettings($element, explode(',', 'text,style,class,id,type,hidden'));
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        $this->processTemplate($context);
    }

    protected function getTemplate(IContext $context)
    {
        return $context->getTemplate($this->type);
    }

    public function __construct(array $element, IContext $context)
    {
        $this->read($element, $context);
    }

    public function getOptions(array $keys)
    {
        $options = $this->attributes;
        foreach($keys as $key){
            if(isset($this->$key) && !is_null($this->$key)) $options[$key] = $this->$key;
        }
        return $options;
    }

    public function renderElement(IContext $context, $aroundHTML)
    {
        if(!$this->hidden) {
            $html = $this->render($context, $aroundHTML);
            $template = $this->template;
            if($template != "") return view($template)
                ->with('element', $this)
                ->with('html', $html)->render();
            return $html;
        }
    }

    protected function render(IContext $context, $aroundHTML)
    {
        if($this->hidden) return;
        // special case
        if($this->type == '_text') return $this->text;
        return $context->renderElement($this, $aroundHTML);
    }

    public function getTag()
    {
        return $this->type;
    }


}
