<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;
use Flatform;

class Select2 extends Input
{
    protected $list;
    protected $selected;
    protected $ajax_url;
    protected $tags;

    protected function read(array $element)
    {
        $this->readSettings($element, ['list', 'ajax-url', 'tags', 'selected']);
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

    protected function renderOptions()
    {
        $html = '';
        if(!is_null($this->list)) {
            foreach($this->list as $key => $value){
                $option = ['value' => $key];
                if(!is_null($selected) && isset($selected[$key])) $option['selected'] = '';
                $html .= $this->context->renderArray($option, 'option', $value);
            }
        } elseif(!is_null($this->selected)) {
            foreach($this->selected as $key => $value){
                $option = ['value' => $key, 'selected' => ''];
                $html .= $this->context->renderArray($option, 'option', $value);
            }
        }
        return $html;
    }

    protected function render()
    {
        $html = $this->addAssets();
        $options = $this->getOptions(['placeholder', 'readonly', 'disabled']);
        if($this->ajax_url) $options['ajax-url'] = $this->ajax_url;
        if($this->tags) $options['tags'] = $this->tags;
        return $html . 
            $this->context->renderArray($options, 'select', $this->renderOptions());
        //Form::select($this->name, $this->list ?? [], $this->value, $options);
    }
}
