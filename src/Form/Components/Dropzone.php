<?php

namespace dimonka2\flatform\Form\Components;
use dimonka2\flatform\Form\Elements\Form;

class Dropzone extends Form
{
    protected const assets = 'dropzone';
    protected $onSuccess;
    protected $onError;
    protected $onAddedfile;
    protected $onRemovedfile;
    protected $onInit;
    protected $fileList;

    protected const options = ['onSuccess', 'onError', 'onRemovedfile', 'onInit', 'onAddedfile'];
    protected $defaultOptions = ['id', 'class', 'style'] + self::options;

    protected function read(array $element)
    {
        $this->readSettings($element, self::options);
        $this->readSettings($element, ['fileList']);
        parent::read($element);
        $this->files = true;
        if($this->fileList) $this->buildFileList();
    }

    protected function buildFileList()
    {

        $this->addHiddenField('files', json_encode($this->fileList));
    }

    public function render()
    {
        $html = $this->addAssets();

        return $html . parent::render();
    }

}
