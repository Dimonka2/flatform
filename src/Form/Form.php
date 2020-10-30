<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Traits\RouteTrait;
use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Form\ElementContainer;

class Form extends ElementContainer implements IForm
{
    use RouteTrait;
    protected const form_fields = ['method', 'model', 'url', 'files', 'route'];
    protected $method;
    protected $model;
    protected $url;
    protected $route;
    protected $files;
    protected $csrfToken;

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
        } 
    }

    protected function getAction()
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route) return self::getRouteUrl($this->route);

        return url()->current();
    }


    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        // 'method', 'url', 'files', 'route'

        $method = strtoupper($this->method);
        $options['method'] = $method !== 'GET' ? 'POST' : $method;
        $options['action'] = $this->getAction();
        $options['accept-charset'] = 'UTF-8';
        
        if ($this->files) {
            $options['enctype'] = 'multipart/form-data';
        }

        return $options;
    }

    public function renderForm($aroundHTML = null)
    {
        $method = strtoupper($this->method);
        if($method !== 'GET') {
            $token = ! empty($this->csrfToken) ? $this->csrfToken : session()->token();
            $this->addHiddenField('_token', $token);
            if(in_array($method, ['DELETE', 'PATCH', 'PUT'])){
                $this->addHiddenField('_method', $method); 
            }
        }

        $this->context->setForm($this);
        $html = $aroundHTML . $this->renderItems();
        $html =  $this->renderer()->renderElement($this, $html);
        $this->context->setForm(null);

        return $html;
    }

    public function addHiddenField($name, $value)
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
