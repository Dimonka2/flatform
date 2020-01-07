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

        if(is_null($this->list)) $this->list = [];
    }

    protected function render()
    {
        $addAssets = !Flatform::isIncluded('bselect');
        if($addAssets) {
            Flatform::include('bselect');
            Flatform::addCSS(config('flatform.assets.bootstrap-select-css'));
            Flatform::addJS(config('flatform.assets.bootstrap-select-js'));
        }
        parent::render();
    }
}
