<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class Element implements IElement
{
    public const template_prefix = "_";
    private $type;
    protected $hidden;
    protected $id;
    protected $class;
    protected $style;
    protected $attributes = [];
    protected $templ_attributes = [];
    protected $_surround = null;
    protected $surround = null;
    protected $text;

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

    protected function read(array $element, IContext $context)
    {
        // echo $this->hash() . " Reading new element: \r\n";
        // print_r($element);
        debug($element);
        $this->readSettings($element, explode(',', 'text,_surround,style,class,id,type,hidden'));
        if(!is_null($this->_surround)) $this->pushSurround($this->_surround, $context);
        if(!is_null($this->hidden)) $this->hidden = !!$this->hidden;
        $this->processTemplate($context);
        debug($this);
        // echo print_r($this->templ_attributes, true) . ' ';
    }

    public function processTemplate(IContext $context)
    {
        // echo "process templates: " . print_r($this->type, true);
        // read properties
        $template = $this->getTemplate($context);
        if (is_array($template)) {
            // add template to the element
            // set this element as a templatable to the context (if possible)
            $is_templatable = $context->setTemplatable($this);
            // print_r($this);
            foreach($template as $attribute => $value) {
                switch ($attribute) {
                    case '_surround':
                        $this->pushSurround($value, $context);
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
            if ($is_templatable) $context->setTemplatable();
        }

    }

    protected function getTemplate(IContext $context)
    {
        return $context->getTemplate($this->type);
    }

    protected function getTemplateAttr($attr)
    {
        return $this->templ_attributes[$attr] ?? null;
    }

    private function processTemplatableAttributes(array &$element, IContext $context)
    {
        $templatable = $context->getTemplatable();
        foreach($element as $attribute => $value) {
            // check for a special prefix
            if(substr($attribute, 0, 1) == self::template_prefix) {
                $new_attr = substr($attribute, 1);
                unset($element[$attribute]);
                if(is_object($templatable)) { 
                    if(is_array($value)) {

                    } else {
                        $new_val = $templatable->getTemplateAttr($value);
                        if(!is_null($new_val)) {
                            $element[$new_attr] = $new_val;
                        }
                    }
                } else {
                    $this->templ_attributes[$new_attr] = $value;
                }
            }
        }
    }

    public function __construct(array $element, IContext $context)
    {
        $this->processTemplatableAttributes($element, $context);
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

    public function setSurround(IElement $surround)
    {
        // echo $this->hash() . ": Element.setSurround \r\n";
        if(is_object($this->surround)) {
            $this->surround->setSurround( $surround );
        } else {
            $this->surround = $surround;
        }
    }

    protected function pushSurround(array $surround, IContext $context)
    {
        // echo $this->hash() . ": Element.pushSurround \r\n";
        $item = $context->createElement($surround);
        $this->setSurround($item);
    }

    public function renderElement(IContext $context, $aroundHTML)
    {
        if(!$this->hidden) {
            $html = $this->render($context, $aroundHTML);
            if(is_object($this->surround)) {
                $html = $this->surround->renderElement($context, $html);
            }
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
