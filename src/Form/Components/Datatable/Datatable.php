<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use Illuminate\Http\Request;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Helpers\DatatableAjax;
use dimonka2\flatform\Form\Components\Datatable\DTColumn;

class Datatable extends ElementContainer
{
    protected const assets = 'datatable';
    use ColumnsTrait;
    use FiltersTrait;

    public const default_data_id = "id";
    public $ajax_url;
    public $ajax_dataType;  // json
    public $ajax_method = 'post';    // POST
    public $order;
    public $options;
    public $option_function;
    public $js_variable;
    public $ajax_data_function;
    public $data_id;
    protected $details;
    protected $select;

    protected $null_last;
    protected $formatFunction;

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $filters = self::readSingleSetting($element, 'filters');
        $this->createFilters($filters ?? []);

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
            'option_function',
            'null_last',
            'formatFunction',
        ]);
        parent::read($element);
        $this->requireID();
    }

    protected function createSelect(array $select)
    {
        $this->select = $this->createElement($select, 'dt-select');
        $this->select->setParent($this);
    }

    protected function createDetails(array $details)
    {
        $this->details = $this->createElement($details, 'dt-details');
        $this->details->setParent($this);
    }


    public function render()
    {
        return $this->context->renderView(
            view(Flatform::config('flatform.assets.datatable.render', 'flatform::datatable'))
                ->with('element', $this)
        ) . $this->addAssets();

    }


    public function columnOffset(): int
    {
        $offset = ($this->hasDetails() ? 1 : 0) + ($this->hasSelect() ? 1 : 0);
        return $offset;
    }

    private function _formatOrder($key = null, $column = null)
    {
        if($column) {
            return [ $key + $this->columnOffset() , $column->sortDesc ? 'desc' : 'asc' ];
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
                    if($column) return [ $key + $this->columnOffset()  , $this->order[1] ];
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
        return $this->_formatOrder();
    }

    public function getTableOptions()
    {
        $options = is_array($this->options) ? $this->options : [];
        $options['processing'] = true;
        $options['serverSide'] = true;
        $options['responsive'] = true;
        // $options['lengthChange'] = false;
        if(Flatform::config('flatform.assets.datatable.lang')) $options["language"] = ['url' =>
            asset(Flatform::config('flatform.assets.datatable.path') . \App::getLocale() . '.json' )];

        if($this->order)  $options['order'] = $this->formatOrder();
        if($this->hasSelect()) $options['select'] = [
                'style' => 'multi',
                'selector' =>  'td:first-child input',
            ];

        $options['ajax'] = [
            "url" => $this->ajax_url ,
            "dataType" =>  $this->ajax_dataType ?? 'json',
            "type" => $this->ajax_method ?? 'GET',
        ];

        $columns = [];
        if($this->hasSelect()){
            $select = $this->select;
            $columns[] = [
                'className' => trim($select->class),
                'orderable' =>      false,
                'data' => '',
                'checkboxes' => [ 'selectRow' => true ],
                'defaultContent' => '', // $select->column_data ?  $select->column_data :  DatatableSelect::column_data,
            ];
        }
        if($this->hasDetails()) {
            $details = $this->details;
            $columns[] = [
                'className' => trim($details->class),
                'orderable' =>      false,
                'data' => '',
                'defaultContent' => $details->column_data ? $details->column_data : DatatableDetails::column_data,
            ];
        }
        foreach($this->columns as $column) {
            $columns[] = $column->getColumnDefs();
        }

        $options ['columns'] = $columns;
        return json_encode($options, JSON_PRETTY_PRINT);
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
        if($request->has('filter')) $query = $this->filters->process($request->filter, $query);
        return DatatableAjax::process($request, $this, $query);
    }

    public function filterDropdown()
    {
        return $this->filters->getDropdown();
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

    public function hasFilter()
    {
        return is_object($this->filters) && $this->filters->isEnabled();
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

    public function  getDataId()
    {
        return $this->data_id ? $this->data_id : self::default_data_id;
    }
}
