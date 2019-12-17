<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\ElementContainer;
use Flatform;

class Datatable extends ElementContainer
{
    public $ajax_url;
    public $ajax_dataType;  // json
    public $ajax_method;    // POST
    public $columns;
    public $order;
    public $options;
    public $js_variable;
    public $ajax_data_function;

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'ajax_url',
            'ajax_dataType',
            'ajax_method',
            'columns',
            'order',
            'options',
            'js_variable',
            'ajax_data_function',
        ]);
        parent::read($element);
        $this->requireID();
    }

    protected function render()
    {
        $addAssets = !Flatform::isIncluded('datatable');
        if($addAssets) Flatform::include('datatable');
        return $this->context->renderView(
            view(config('flatform.assets.datatable'))
                ->with('element', $this)
                ->with('addAssets', $addAssets)
        );

    }
}
