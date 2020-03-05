<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

class Select2 extends Input
{
    protected const assets = 'select2';
    protected $list;
    protected $selected;
    protected $ajax_url;
    protected $tags;

    protected function read(array $element)
    {
        $this->readSettings($element, ['list', 'ajax-url', 'tags', 'selected']);
        parent::read($element);
    }


    protected function renderOptions()
    {
        $html = '';
        if(!is_null($this->list)) {
            foreach($this->list as $key => $value){
                $option = ['value' => $key];
                if(!is_null($this->selected) && isset($this->selected[$key])) $option['selected'] = '';
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

    /**
     * Get the value of selected
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set the value of selected
     *
     * @return  self
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }
}
