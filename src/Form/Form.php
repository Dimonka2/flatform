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

    public function read(array $element)
    {
        $this->readSettings($element, self::form_fields);
        parent::read($element);
    }

    protected function render()
    {
        $options = $this->getOptions(['method', 'url', 'files']);
        if(!is_object($this->model)) {
            $html = LaForm::model($this->model , $options);
        } else  {
            $html = LaForm::open($this->model , $options);
        }
        $html .= $this->renderItems();
        $html .= LaForm::close();
        return $html;
    }
}
