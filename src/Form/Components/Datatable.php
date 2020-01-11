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
        if(!Flatform::isIncluded('datatable')) {
            Flatform::include('datatable');
            $path = config('flatform.assets.datatable_path');
            Flatform::addCSS(config('flatform.assets.datatable_css'), $path);
            Flatform::addJS(config('flatform.assets.datatable_js'), $path);
        }
        return $this->context->renderView(
            view(config('flatform.assets.datatable'))
                ->with('element', $this)
        );

    }
}
