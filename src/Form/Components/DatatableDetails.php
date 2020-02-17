<?php

namespace dimonka2\flatform\Form\Components;

use Illuminate\Http\Request;
use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Form\Contracts\IElement;

class DatatableDetails extends Element
{
    public const ajax_parameter = "dt_details";
    public const default_class = "dt-details";
    public const default_data_id = "id";
    public $data_definition;
    public $format_function;
    public $loaded_function;
    public $column_data;
    public $data_id;
    protected $has_ajax;
    protected $url;
    protected $ajax_method;
    protected $formatFunction;
    protected $table;

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'url',
            'data_definition',          // 'article_id: rowData.id,'
            'format_function',          // myFormat(rowData);
            'loaded_function',          // initSomething(element);
            'ajax_method',
            'column_data',
            'data_id',
            'has-ajax',
        ]);
        parent::read($element);
        if(!$this->class ) $this->class = self::default_class;
        if(!$this->data_id ) $this->data_id = self::default_data_id;
    }

    public function getDataDefinition()
    {
        $definition = '';
        if ($this->data_id) $definition .= $this->data_id . ' : rowData.' . $this->data_id . ",\r\n";
        if($this->has_ajax && !$this->url) $definition .= self::ajax_parameter . ": '',\r\n";
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
        return ($this->has_ajax != false) && ($this->url ||  $this->table->ajax_url);
    }
}
