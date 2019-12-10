<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Form as LaForm;

class Form extends ElementContainer
{
    protected const form_fields = ['method', 'model', 'url', 'files'];
    protected $method;
    protected $model;
    protected $url;
    protected $files;

    public function read(array $element, IContext $context)
    {
        $this->readSettings($element, self::form_fields);
        parent::read($element, $context);
    }

    protected function render(IContext $context, $aroundHTML)
    {
        $options = $this->getOptions(['method', 'url', 'files']);
        if(!is_object($this->model)) {
            $html = LaForm::model($this->model , $options);
        } else  {
            $html = LaForm::open($this->model , $options);
        }
        $html .= $aroundHTML;
        $html .= $this->renderItems($context);
        $html .= LaForm::close();
        return $html;
    }
}
