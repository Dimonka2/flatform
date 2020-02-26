<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Form\Element;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Contracts\IElement;

class DatatableSelect extends Element
{
    public const ajax_parameter = "dt_select";
    public const default_data_id = "id";
    public const default_class = "dt-select";
    public const column_data = '<input type="checkbox" class="dt-select mt-1" />';
    protected $url;
    protected $data_id;
    protected $has_ajax;
    protected $selectFunction;
    protected $table;
    public $column_data;

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'url',
            'data_id',
            'has-ajax',
            'column-data',
            'selectFunction',
        ]);
        parent::read($element);
        if(!$this->data_id ) $this->data_id = self::default_data_id;
        if(!$this->class ) $this->class = self::default_class;
    }

    public function setParent(IElement $item)
    {
        if($item instanceof Datatable) {
            $this->table = $item;
        }
        $res = parent::setParent($item);
        return $res;
    }

    public function getHasAjax()
    {
        return ($this->has_ajax != false) && ($this->url ||  $this->table->ajax_url);
    }

    public function hasSelectFunction()
    {
        return is_callable($this->selectFunction);
    }

    public function select(Request $request)
    {
        return call_user_func_array($this->selectFunction, [$request, $this->table]);
    }

    /**
     * Set the value of selectFunction
     *
     * @return  self
     */
    public function setSelectFunction($selectFunction)
    {
        $this->selectFunction = $selectFunction;

        return $this;
    }

    public function getCheckBox($header = false)
    {
        $checkbox = ['table-checkbox', '+class' => $this->class];
        if($header) {
            $checkbox['+class'] .= ' group-select';
        } else {
            $checkbox['id'] = '_placeholder';
        }
        return json_encode(FlatformService::render([$checkbox]));
    }
}
