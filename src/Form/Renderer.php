<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Contracts\IRenderer;

class Renderer implements IRenderer
{
    public const VOID_TAGS = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param',
    'source', 'track', 'wbr'];

    protected $context;
    public function __construct(Context $context = null)
    {
        if(!$context) $context = Flatform::context();
        $this->context = $context;
    }

    public function render($elements = null)
    {
        if ($this->context->getDebug()) {
            debug($this);
            debug($elements);
        }
        if(is_object($elements)){
            return $elements->renderElement();
        }

        return $this->renderItem($elements);
    }

    public function renderView($view, ?string $toStack = null)
    {
        $html = $view->with('context', $this)->render();
        if(!$toStack) return $html;
        $push = $this->context->createElement(['push', 'name' => $toStack, 'text' => $html]);
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
            $div = new ElementContainer([], $this->context);
            return $div->readItems($item)->renderItems();
        }
        if(is_object($item)) return $item->renderElement();
        return $item;
    }

}
