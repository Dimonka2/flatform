<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;
use Flatform;

class BootstrapSelect extends Input
{
    public $color;
    protected $options;
    protected $state_list;
    protected $list;
    protected $ajax_url;

    protected function read(array $element)
    {
        $this->readSettings($element, ['color', 'state_list', 'list', 'ajax-url']);
        parent::read($element);
        $this->processOptions();
    }

    public function __construct(array $element, IContext $context)
    {
        $this->options = new ElementContainer([], $context);
        parent::__construct($element, $context);
    }

    private function processOptions()
    {
        $options = [];
        if(is_array( $this->list)) {
            foreach($this->list as $key => $option) {
                if(is_array($option)) {
                    // check if type is data-divider
                    $option['type'] = 'option';
                    $item = $this->createElement($option);
                }elseif(is_object($option)) {
                    $item = $this->createElement(
                        ['type' => 'option', 'value' => $option->id, 'text' => $option->name, 'icon' => $option->icon]
                    );
                } else {
                    $item = $this->createElement(['type' => 'option', 'value' => $key, 'text' => $option]);
                }
                $icon = $item->getAttribute('icon');
                if($icon != '') {
                    $item->setAttribute('data-icon', $icon);
                }
                // render closure
                $item->onRender = function($item, $context) {
                    return $context->renderElement($item, $item->text);
                };

                $this->options->push($item);

            }
        }
    }

    protected function addAssets()
    {
        if(!Flatform::isIncluded('bselect')){
            Flatform::include('bselect');
            Flatform::include('bselect');
            Flatform::addCSS(config('flatform.assets.bootstrap-select-css'));
            Flatform::addJS(config('flatform.assets.bootstrap-select-js'));
            return $this->context->renderView(
                view(config('flatform.assets.bootstrap-select'))
            );
        }
    }

    protected function renderList()
    {
        return $this->options->renderItems();
    }

    protected function render()
    {
        // logger('rendering', [$this]);
        $html = $this->addAssets();
        return $this->context->renderElement($this, $this->renderList()) . $html;
    }
}
