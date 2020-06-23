<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IElement;

class BootstrapSelect extends Select
{
    protected const assets = 'bselect';

    public $color;
    protected $options;
    protected $ajax_url;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'ajax-url']);
        parent::read($element);
        if(is_null($this->selected)) {
            $value = $this->needValue();
            if($value) $this->selected[$value] = true;
        }
    }

    public function __construct(array $element = [], ?IContext $context = null)
    {
        $this->options = new ElementContainer([], $context);
        parent::__construct($element, $context);
    }

    private function processOptions()
    {
        $this->options = [];
        if(is_iterable( $this->list)) {
            foreach($this->list as $key => $option) {
                if(is_array($option)) {
                    // check if type is data-divider
                    $item = $this->createElement($option, 'option');
                }elseif(is_object($option)) {
                    $item = $this->createElement(
                        ['type' => 'option', 'value' => $option->id, 'text' => $option->name, 'icon' => $option->icon]
                    );
                } else {
                    $item = $this->createElement(['option', 'value' => $key, 'text' => $option]);
                }
                $icon = $item->getAttribute('icon');
                if($icon != '') {
                    $item->setAttribute('data-icon', $icon);
                }
                if(!is_null($this->selected) && isset($this->selected[$item->getAttribute('value')])) $item->setAttribute('selected','');
                // render closure
                $item->setOnRender(function(IElement $item) {
                    return $item->getContext()->renderElement($item, $item->text);
                });

                $this->options[] = $item;

            }
        }
    }

    protected function renderList()
    {
        if(is_array($this->options)) return $this->renderItem($this->options);
        return $this->options->renderItems();
    }

    public function render()
    {
        // lazy processing of options
        if(!is_array($this->options)) $this->processOptions();
        // logger('rendering', [$this]);
        $html = $this->addAssets();
        return $this->context->renderElement($this, $this->renderList()) . $html;
    }
}
