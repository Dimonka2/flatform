<?php

namespace dimonka2\flatform\Form\Components\JsTree;

use dimonka2\flatform\Traits\SettingReaderTrait;

class Ajax
{
    use SettingReaderTrait;
    protected $url;
    protected $dataFunction;

    public function __construct(array $element = [])
    {
        $this->readSettings($element, ['url', 'dataFunction']);
    }

    public function toArray()
    {
        return ['url' => $this->url];
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
     * Get the value of dataFunction
     */
    public function getDataFunction()
    {
        return $this->dataFunction;
    }

    public function hasDataFunction()
    {
        return !!$this->dataFunction;
    }

    /**
     * Set the value of dataFunction
     *
     * @return  self
     */
    public function setDataFunction($dataFunction)
    {
        $this->dataFunction = $dataFunction;

        return $this;
    }
}
