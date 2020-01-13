<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Form as LaForm;

class Form extends ElementContainer
{
    protected const form_fields = ['method', 'model', 'url', 'files', 'route'];
    protected $method;
    protected $model;
    protected $url;
    protected $route;
    protected $files;

    public function read(array $element)
    {
        $this->readSettings($element, self::form_fields);
        parent::read($element);
    }

    public function renderForm($aroundHTML = null)
    {
        $options = $this->getOptions(['method', 'url', 'files', 'route']);
        if(is_object($this->model)) {
            $html = LaForm::model($this->model , $options);
        } else  {
            $html = LaForm::open($options);
        }
        $html .= $aroundHTML;
        $html .= $this->renderItems();
        $html .= LaForm::close();

        return $html;
    }

    protected function render()
    {
        return $this->renderForm();
    }
}
