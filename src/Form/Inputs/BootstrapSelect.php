<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class BootstrapSelect extends Input
{
    public $color;
    protected $state_list;
    protected $list;
    protected $ajax_url;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'state_list', 'list', 'ajax-url']);
        parent::read($element);

        if(is_array($this->title)) {
            $this->title = $this->createContainer($this->title);
        }

        if(is_array($this->text)) {
            $this->text = $this->createContainer($this->text);
        }
    }

    protected function render()
    {
        $addAssets = !Flatform::isIncluded('datatable');
        if($addAssets) Flatform::include('datatable');
        parent::render();
    }
}
