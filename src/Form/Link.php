<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Link extends ElementContainer
{
    protected $href;
    protected $post;

    public function read(array $element, IContext $context)
    {
        $this->readSettings($element, ['href', 'post', 'title']);
        parent::read($element, $context);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if(!is_null($this->href) && is_null($this->post)) $options['href'] = $this->href;
        return $options;
    }

    protected function render(IContext $context, $aroundHTML)
    {
        if(!is_null($this->post)) {
            // render form
            if(is_array($this->post)) {

            } else {
                $method = $this->post;
            }
            $form = [
                'type' => 'form',
                'method' => $method,
                'url' => $this->href,
            ];
            $surround = $context->createElement($form);
            $surround->read($form, $context);
            return $surround->renderElement($context, $context->renderElement($this, $aroundHTML));

        } else  {
            return $context->renderElement($this, $aroundHTML);
        }
    }

}
