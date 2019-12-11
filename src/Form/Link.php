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

    protected function is_post()
    {
        return !is_null($this->post);
    }

    protected function is_link()
    {
        return !is_null($this->href) && !$this->is_post();
    }

    protected function read(array $element)
    {
        $this->readSettings($element, ['href', 'post', 'title', 'icon']);
        parent::read($element);
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        if($this->is_link()) $options['href'] = $this->href;
        if($this->is_post()) $options['type'] = 'submit';
        return $options;
    }

    protected function getTitle()
    {
        return $this->title;
    }

    protected function renderForm()
    {
        // render form
        $form = $this->context->createTemplate('link-form');
        $method = 'POST';
        if(is_array($this->post)) {
            foreach($this->post as $key => $item) {
                if($item == 'post') {
                    $method = $item;
                } else {
                    $form->elements->push($this->context->createElement([
                        'type' => 'hidden',
                        'name' => $key,
                        'value' => $item,
                    ]));
                }
            }
        } else {
            $method = $this->post;
        }
        $form->read(['method' => $method, 'url' => $this->href]);
        // dd($form);
        return $form->renderForm( $this->renderLink() );

    }

    protected function renderLink()
    {
        return $this->context->renderElement($this, $this->getTitle());
    }

    protected function render()
    {
        if($this->is_post()) {
            return $this->renderForm();
        } else  {
            return $this->renderLink();
        }
    }

    public function getTag()
    {
       return $this->is_link() ? 'a' : 'button';
    }
}
