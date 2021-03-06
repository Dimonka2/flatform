<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use Illuminate\Database\Eloquent\Model;

class Select2 extends Input
{
    protected const assets = 'select2';
    protected $list;
    protected $selected;
    protected $ajax_url;
    protected $tags;
    public $wire_ignore = 1;

    protected function read(array $element)
    {
        $this->readSettings($element, ['list', 'ajax-url', 'tags', 'selected']);
        parent::read($element);
    }


    protected function renderOptions()
    {
        $html = '';
        $selected = $this->selected;
        if(!is_null($this->list)) {
            foreach($this->list as $key => $value){
                $option = ['value' => $key];
                if(!is_null($this->selected) && isset($this->selected[$key])) $option['selected'] = '';
                $html .= $this->context->renderArray($option, 'option', $value);
            }
        } elseif(!is_null($selected)) {
            if(is_countable($selected)) {
                foreach($selected as $key => $value){
                    $option = ['value' => $key, 'selected' => ''];
                    $html .= $this->context->renderArray($option, 'option', $value);
                }
            } elseif($selected instanceof Model) {
                $option = ['value' => $selected->getKey(), 'selected' => ''];
                $html .= $this->context->renderArray($option, 'option', $selected->name ?? $selected->title);
            }
        }
        return $html;
    }

    public function render()
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
