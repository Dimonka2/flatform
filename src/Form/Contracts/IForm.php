<?php

namespace dimonka2\flatform\Form\Contracts;

interface IForm extends IContainer
{
    public function hasModel();
    public function getModel();
    public function setModel($model);

    public function getUrl();
    public function setUrl($url);

    public function setMethod($method);
    public function getMethod();

    public function getFiles();
    public function setFiles($files);

}
