<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\Contracts\IForm;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Inputs\Hidden;
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
        }
    }

    protected function getAction()
    {
        if($url = $this->url) return is_array($url) ? url()->to($url[0], array_slice($url, 1)) : url()->to($url);
        if($route = $this->route) {
            if (is_array($route)) {
                $parameters = array_slice($route, 1);

                if (array_keys($route) === [0, 1]) {
                    $parameters = head($parameters);
                }

                return route($route[0], $parameters);
            }

            return route($route);
        }
    }

    public function render()
    {
        $this->context->setForm($this);
        $html = parent::render();
        $this->context->setForm(null);
        return $html;
    }

    protected function isGetMethod()
    {
        return trim(strtolower($this->method)) == 'get';
    }

    public function renderItems()
    {
        $html = parent::renderItems();
        if(!$this->isGetMethod()) {
            $html .= $this->context->renderArray(
                    ['type' => 'hidden', 'name' => '_token', 'value' => csrf_token()], 'input');
            if(trim(strtolower($this->method)) != 'post') $html .= $this->context->renderArray(
                    ['type' => 'hidden', 'name' => '_method', 'value' => $this->method], 'input');
        }
        return $html;
    }

    public function getOptions(array $keys)
    {
        $options = parent::getOptions($keys);
        $options['method'] = $this->isGetMethod() ? 'get' : 'post';
        $options['accept-charset'] = 'UTF-8';
        $options['action'] = $this->getAction();
        if($this->files) $options['enctype'] = 'multipart/form-data';
        return $options;
    }

    public function addHiddenField($name, $value)
    {
        $this[] = $this->createElement(['hidden', 'name' => $name, 'value' => $value]);

        return $this;
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
