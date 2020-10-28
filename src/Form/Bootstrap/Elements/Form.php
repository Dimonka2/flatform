<?php

namespace dimonka2\flatform\Form\Bootstrap\Elements;

use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Bootstrap\Inputs\Hidden;
use Form as LaForm;

class Form extends ElementContainer implements IForm
{
    protected const form_fields = ['method', 'model', 'url', 'files', 'route'];
    protected $method;
    protected $model;
    protected $url;
    protected $route;
    protected $files;

    protected function read(array $element)
    {
        $this->context->setForm($this);

        $this->readSettings($element, self::form_fields);
        parent::read($element);
        $this->context->setForm(null);
    }

    public function getModelValue($name)
    {
        if($this->hasModel()){
            return data_get($this->model, $name);
        } else {
            return LaForm::getValueAttribute($name);
        }
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
        $this->context->setForm($this);
        $html .= $this->renderItems();
        $this->context->setForm(null);
        $html .= LaForm::close();

        return $html;
    }

    public function addHiddenField($name, $value): Hidden
    {
        $item = $this->createElement(['hidden', 'name' => $name, 'value' => $value]);
        $this[] = $item;

        return $item;
    }

    public function render()
    {
        return $this->renderForm();
    }

    /**
     * Get the value of url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get the value of method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the value of method
     *
     * @return  self
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get the value of files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set the value of files
     *
     * @return  self
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get the value of route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the value of route
     *
     * @return  self
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    // IForm interface
    public function hasModel()
    {
        return is_object($this->model);
    }

    public function getModel()
    {
        return $this->model;
    }


    /**
     * Set the value of model
     *
     * @return  self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
