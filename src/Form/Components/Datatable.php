<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Components\DTColumn;
use dimonka2\flatform\Helpers\DatatableAjax;
use Illuminate\Http\Request;
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
    protected $details;
    protected $colDefinition;   // collection of DTColumn objects
    protected $null_last;
    protected $formatFunction;

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $details = self::readSingleSetting($element, 'details');
        if(is_array($details)) $this->createDetails($details);

        $this->readSettings($element, [
            'ajax_url',
            'ajax_dataType',
            'ajax-method',
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
    protected function createDetails(array $details)
    {
        $this->details = new DatatableDetails($details, $this->context);
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

    protected function getColumn($index, &$key)
    {
        if(is_integer($index)) {
            $key = $index;
            return $this->colDefinition[$column];
        }
        $key = $this->colDefinition->search(function  ($item, $key) use($index) {
            return $item->name == $index;
        });
        if($key !== false) return $this->colDefinition[$key];
        return;
    }

    private function _formatOrder($key = null, $column = null)
    {
        if($column) {
            return '[' . ($key + ($this->details ? 1 : 0)) . ', "' . ($column->sortDesc ? 'desc' : 'asc'). '"]';
        }
        if(is_array($this->order)) {
            $cnt = count($this->order);
            switch ($cnt) {
                case 1:
                    $column = $this->getColumn($this->order[0], $key);
                    if($column) return $this->_formatOrder($key, $column);
                    return null;
                case 2:
                    $column = $this->getColumn($this->order[0], $key);
                    if($column) return '[' . ($key + ($this->details ? 1 : 0)) . ', "' . $this->order[1] . '"]';
                    return null;
                default:
                    return;
            }
        } else {
            $column = $this->getColumn($this->order, $key);
            if($column) return $this->_formatOrder($key, $column);
            return;
        }
    }

    public function formatOrder()
    {
        if(is_string($this->order) && strpos($this->order, ',') > 0) return $this->order;
        return 'order: [' . $this->_formatOrder() . '], ' . PHP_EOL;
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

    /**
     * Set the value of formatFunction
     *
     * @return  self
     */
    public function setFormatFunction($formatFunction)
    {
        $this->formatFunction = $formatFunction;
        return $this;
    }

    public function hasFormatter()
    {
        return is_callable($this->formatFunction);
    }

    public function format($data, $item, DTColumn $column)
    {
        return call_user_func_array($this->formatFunction, [$data, $column, $item]);
    }

    public function processAJAX(Request $request, $query)
    {
        return DatatableAjax::process($request, $this, $query);
    }


    /**
     * Get the value of details
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function hasDetails()
    {
        return is_object($this->details);
    }

    /**
     * Get the value of ajax_method
     */
    public function getAjaxMethod()
    {
        return $this->ajax_method;
    }

    /**
     * Set the value of ajax_url
     *
     * @return  self
     */
    public function setAjaxUrl($ajax_url)
    {
        $this->ajax_url = $ajax_url;
        return $this;
    }
}
