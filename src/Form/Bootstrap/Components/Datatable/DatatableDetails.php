<?php

namespace dimonka2\flatform\Form\Bootstrap\Components\Datatable;

use Illuminate\Http\Request;
use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IElement;

class DatatableDetails extends Element
{
    public const ajax_parameter = "dt_details";
    public const default_class = "dt-details";
    public const column_data = '<button class="btn btn-sm btn-clean btn-icon btn-icon-md dt-details"><i class="fa fa-caret-down"></i></button>';
    public $data_definition;    // 'article_id: rowData.id,'
    public $format_function;    // JS function myFormat(rowData);
    public $loaded_function;    // JS function initSomething(element);
    public $column_data;
    protected $has_ajax;
    protected $url;
    protected $ajax_method;
    protected $formatFunction;  // PHP format function
    protected $table;

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'url',
            'data_definition',
            'format_function',
            'loaded_function',
            'formatFunction',
            'ajax_method',
            'column_data',
            'has-ajax',
        ]);
        parent::read($element);
        if(!$this->class ) $this->class = self::default_class;
    }

    public function getDataDefinition()
    {
        $definition = '';
        $data_id = $this->table->getDataId();
        $definition .= $data_id . ' : rowData.' . $data_id . ",\r\n";
        if($this->getHasAjax()) $definition .= self::ajax_parameter . ": '',\r\n";
        $definition .= $this->data_definition;
        return $definition;
    }

    /**
     * Get the value of ajax_method
     */
    public function getAjaxMethod()
    {
        return $this->ajax_method ? $this->ajax_method : $this->table->getAjaxMethod();
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

    public function format(Request $request)
    {
        return call_user_func_array($this->formatFunction, [$request, $this->table]);
    }


    public function setParent(IElement $item)
    {
        if($item instanceof Datatable) {
            $this->table = $item;
        }
        $res = parent::setParent($item);
        return $res;
    }

    /**
     * Get the value of url
     */
    public function getUrl()
    {
        return $this->url ? $this->url : $this->table->ajax_url ;
    }

    /**
     * Get the value of has_ajax
     */
    public function getHasAjax()
    {
        return ($this->has_ajax !== false) && ($this->url ||  $this->table->ajax_url);
    }
}
