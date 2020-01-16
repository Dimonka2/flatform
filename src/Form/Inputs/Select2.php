<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;
use Flatform;

class Select2 extends Input
{
    protected $list;
    protected $ajax_url;
    protected $tags;

    protected function read(array $element)
    {
        $this->readSettings($element, ['list', 'ajax-url', 'tags']);
        if(is_null($this->list)) $this->list = [];
        parent::read($element);
    }

    protected function addAssets()
    {
        if(!Flatform::isIncluded('select2')){
            Flatform::include('select2');
            $path = config('flatform.assets.select2.path');
            Flatform::addCSS(config('flatform.assets.select2.css'), $path);
            Flatform::addJS(config('flatform.assets.select2.js'), $path);
            return $this->context->renderView(
                view(config('flatform.assets.select2.view'))
            );
        }
    }

    protected function render()
    {
        $html = $this->addAssets();
        $options = $this->getOptions(['placeholder', 'readonly', 'disabled']);
        if($this->ajax_url) $options['ajax-url'] = $this->ajax_url;
        if($this->tags) $options['tags'] = $this->tags;
        return $html . Form::select($this->name, $this->list ?? [], $this->value, $options);
    }
}
