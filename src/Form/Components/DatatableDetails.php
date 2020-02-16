<?php

namespace dimonka2\flatform\Form\Components;

use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Element;

class DatatableDetails extends Element
{
    public const default_class = "dt-details";
    public const default_data_id = "id";
    public $url;
    public $data_definition;
    public $format_function;
    public $loaded_function;
    public $column_data;
    public $data_id;
    protected $ajax_method;

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
        ]);
        parent::read($element);
        if(!$this->class ) $this->class = self::default_class;
        if(!$this->data_id ) $this->data_id = self::default_data_id;
    }

    /**
     * Get the value of ajax_method
     */
    public function getAjaxMethod()
    {
        return $this->ajax_method ? $this->ajax_method : $this->parent->getAjaxMethod();
    }
}
