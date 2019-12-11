<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;
use \dimonka2\flatform\Flatform;

class Select2 extends Input
{
    protected $items;
    protected $ajax_url;

    protected function read(array $element)
    {
        $fields = 'items,ajax-url';
        $this->readSettings($element, explode(',', $fields));
        if(is_null($this->items)) $this->items = [];
        parent::read($element);
    }

    protected function addAssets()
    {
        if(!Flatform::isIncluded('select2')){
            Flatform::include('select2');
            return view(config('flatform.assets.select2'))->render();
        }
    }

    protected function render()
    {
        $html = $this->addAssets();
        $options = $this->getOptions(['placeholder', 'readonly', 'disabled']);
        if(!is_null($this->ajax_url)) $options['ajax-url'] = $this->ajax_url;
        return $html . Form::select($this->name, $this->items, $this->value, $options);
    }
}
