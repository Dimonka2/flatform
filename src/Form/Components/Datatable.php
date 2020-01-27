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
    protected $colDefinition;   // collection of DTColumn objects
    protected $null_last;
    protected $formatFunction;

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);
        $this->readSettings($element, [
            'ajax_url',
            'ajax_dataType',
            'ajax_method',
            'order',
            'options',
            'js_variable',
            'ajax_data_function',
            'null_last',
            'formatFunction',
        ]);
        parent::read($element);
        $this->requireID();
    }

    protected function createColumns(array $columns)
    {
        $this->colDefinition = collect([]);
        foreach($columns as $column) {
            $this->addColumn($column);
        }
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

    public function addColumn(array $definition)
    {
        if(!isset($definition['type'])) $definition['type'] = 'dt-column';
        $column = $this->createElement($definition);
        $column->setParent($this);
        $this->colDefinition->push($column);
        return $column;
    }

    /**
     * Get the value of colDefinition
     */
    public function getColDefinition()
    {
        return $this->colDefinition;
    }

    /**
     * Get the value of null_last
     */
    public function getNullLast()
    {
        return $this->null_last;
    }

    public function hasFormatter()
    {
        return is_callable($this->formatFunction);
    }

    public function format($data, $item, DTolumn $column)
    {
        return $this->formatFunction($data, $column, $item);
    }
}
