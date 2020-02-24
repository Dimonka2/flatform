<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use \dimonka2\flatform\Flatform;
use Illuminate\Http\Request;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Helpers\DatatableAjax;
use dimonka2\flatform\Form\Components\Datatable\DTColumn;

class Datatable extends ElementContainer
{
    public $ajax_url;
    public $ajax_dataType;  // json
    public $ajax_method = 'post';    // POST
    public $order;
    public $options;
    public $js_variable;
    public $ajax_data_function;
    protected $details;
    protected $select;
    protected $columns;   // collection of DTColumn objects
    protected $null_last;
    protected $formatFunction;

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $details = self::readSingleSetting($element, 'details');
        if(is_array($details)) $this->createDetails($details);

        $select = self::readSingleSetting($element, 'select');
        if(is_array($select)) $this->createSelect($select);

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
        $this->columns = new Columns($this);
        foreach($columns as $column) {
            $this->addColumn($column);
        }
    }
    protected function createSelect(array $select)
    {
        $this->select = $this->createElement($select, 'dt-select');
        $this->details->setParent($this);
    }

    protected function createDetails(array $details)
    {
        $this->details = $this->createElement($details, 'dt-details');
        $this->details->setParent($this);
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
        $column = $this->createElement($definition, 'dt-column');
        $this->columns[] = $column;
        return $column;
    }

    public function getColumn($index): ?DTColumn
    {
        return $this->columns->getColumn($index);
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
                    $column = $this->columns->getColumnEx($this->order[0], $key);
                    if($column) return $this->_formatOrder($key, $column);
                    return null;
                case 2:
                    $column = $this->columns->getColumnEx($this->order[0], $key);
                    if($column) return '[' . ($key + ($this->details ? 1 : 0)) . ', "' . $this->order[1] . '"]';
                    return null;
                default:
                    return;
            }
        } else {
            $column = $this->columns->getColumnEx($this->order, $key);
            if($column) return $this->_formatOrder($key, $column);
            return;
        }
    }

    public function formatOrder()
    {
        if(is_string($this->order) && strpos($this->order, ',') > 0) return $this->order;
        return 'order: [' . $this->_formatOrder() . '], ' . PHP_EOL;
    }

    public function getTableOptions()
    {
        $options = $this->options;
        if($this->order)  $options .= $this->formatOrder();
        if($this->hasSelect()) $options .= "\r\n select: true,";

        $columns = [];
        if($this->hasSelect()){
            $select = $this->select;
            $columns[] = [
                'className' => trim($select->class),
                'orderable' =>      false,
                'data' => '',
                'defaultContent' => $select->column_data ?
                    $select->column_data :
                    "<button class='btn btn-sm btn-clean btn-icon btn-icon-md p-1'><i class='fa fa-caret-down'></i></button>",
            ];
        }
        if($this->hasDetails()) {
            $details = $this->details;
            $columns[] = [
                'className' => trim($details->class),
                'orderable' =>      false,
                'data' => '',
                'defaultContent' => $details->column_data ?
                    $details->column_data :
                    "<button class='btn btn-sm btn-clean btn-icon btn-icon-md p-1'><i class='fa fa-caret-down'></i></button>",
            ];
        }
        foreach($this->columns as $column) {
            $columns[] = $column->getColumnDefs();
        }

        $options .= "\r\n columns: " . json_encode($columns) . ',';
        return $options;
    }

    /**
     * Get the value of Columns
     */
    public function getColumns()
    {
        return $this->columns;
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
    public function setFormatFunction($formatFunction, $columnName = null)
    {
        if($columnName) {
            $this->columns->setFormatFunction($formatFunction, $columnName);
        } else {
            $this->formatFunction = $formatFunction;
        }
        return $this;
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
        // built in details!!
        if($this->hasDetails() && $request->has(DatatableDetails::ajax_parameter)) {
            $details = $this->getDetails();
            if($details->hasFormatter()) return $details->format($request);
            return response()->json(['error' => 'Table has no details formatter!'], 400);
        }
        if($this->hasSelect() && $request->has(DatatableSelect::ajax_parameter)) {
            $select = $this->getSelect();
            if($select->hasSelectFunction()) return $select->select($request);
            return response()->json(['error' => 'Table has no "select function"!'], 400);
        }

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
     * Get the value of select
     */
    public function getSelect()
    {
        return $this->select;
    }

    public function hasSelect()
    {
        return is_object($this->select);
    }
}
