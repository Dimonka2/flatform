<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

class Link extends ElementContainer
{
    protected $href;
    protected $post;
    public $title;
    public $icon;

    protected function read(array $element)
    {
        $this->readSettings($element, ['href', 'post', 'title', 'icon']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if(!is_null($this->href) && is_null($this->post)) $options['href'] = $this->href;
        return $options;
    }

    protected function getTitle()
    {
        return $this->title;
    }

    protected function render()
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
            $surround = $this->context->createElement($form);
            $surround->read($form);
            return $surround->renderElement( $this->context->renderElement($this, $this->getTitle()));

        } else  {
            return $this->context->renderElement($this, $this->getTitle());
        }
    }

}
