<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Context;
use Illuminate\Support\Collection;

class Link extends ElementContainer
{
    protected $href;
    protected $post;
    protected $title;

    public function read(array $element, Context $context)
    {
        $this->readSettings($element, ['href', 'post', 'title']);
        parent::read($element, $context);
        if($this->text != '') $this->addTextElement($context, $this->text);
    }

    public function render(Context $context)
    {
        if(!is_null($this->post)) {
            // render form

        } else  {
            return $context->renderElement($this);
        }
    }
}
