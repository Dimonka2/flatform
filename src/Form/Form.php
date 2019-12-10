<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Illuminate\Support\Collection;
use Form as LaForm;

class Form extends ElementContainer
{
    protected const form_fields = ['method', 'model', 'route', 'files'];
    protected $method;
    protected $model;
    protected $route;
    protected $files;

    public function read(array $element, IContext $context)
    {
        $this->readSettings($element, self::form_fields);
        parent::read($element, $context);
    }

    public function render(IContext $context)
    {
        $options = $this->getOptions(['method', 'route', 'files']);
        if(!is_object($this->model)) {
            $html = LaForm::model($this->model , $options);
        } else  {
            $html = LaForm::open($this->model , $options);
        }
        $html .= $this->renderItems($context);
        $html .= LaForm::close();
        return $html;
    }
}
