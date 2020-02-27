<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Form\Contracts\IData;
use dimonka2\flatform\Form\Contracts\IDataProvider;
use dimonka2\flatform\Form\Element;
use dimonka2\flatform\Helpers\ColumnFormat;

class DTColumn extends Element implements IDataProvider
{
    public $name;           // field name, mapped as "data"
    public $as;             // field alias
    public $title;          // column title
    public $search;         // enables search
    public $sort;           // disable sort by this column
    public $orderSequence;  // defines order sequense, like ["desc", "asc"]
    public $sortDesc;       // force "orderSequence": ["desc", "asc"]
    public $defs;           // all oparameters - should be depricated in the future
    public $system;         // not a database field (no sorting)
    public $noSelect;       // exclude from select statement of query
    public $contentPadding; // like "mmmm"  https://datatables.net/reference/option/columns.contentPadding
    protected $formatFunction; // a closure to format the cell function($data, DTColumn $column, $item)
    protected $format;      // formatting description in flatform format, with special item "value"
    protected $dataElements = [];

    protected function read(array $element)
    {
        $this->context->setDataProvider($this);
        $format = $this->readSingleSetting($element, 'format');
        if($format) $this->createFormatter($format);
        $this->readSettings($element, [
            'name',
            'as',
            'title',
            'search',
            'defs',
            'system',
            'noSelect',
            'sort',
            'formatFunction',
            'orderSequence',
            'sortDesc',
            'contentPadding',
        ]);
        parent::read($element);
        if(!$this->as && strpos($this->name, '.')) $this->as = str_replace('.', '__', $this->name);
        $this->context->setDataProvider(null);
    }

    public function getColumnDefs(): array
    {
        $defs = $this->getOptions(['title', 'class']);
        $defs['data'] = $this->getSafeName();
        if($this->title !== null) $defs['title'] = $this->title;
        if($this->hidden) $defs['visible'] = false;
        if($this->system or $this->sort === false) {
            $defs['orderable'] = false;
        } else{
            if($this->sortDesc) $defs['orderSequence'] =  ["desc", "asc"];
            if($this->orderSequence) $defs['orderSequence'] =  $this->orderSequence;
        }
        return $defs;
    }

    protected function createFormatter($format)
    {
        $this->format = ColumnFormat::make($format);
    }

    public function registerDataElement(IData $element)
    {
        $this->dataElements[] = $element;
    }

    public function hasFormatter()
    {
        return is_callable($this->formatFunction) || $this->format;
    }

    public function format($data, $item)
    {
        $html = '';
        if($this->format){
            foreach ($this->dataElements as $data) {
                $data->setData($data, $item);
            }
            $html .= $this->format->renderElement();
        }
        if (is_callable($this->formatFunction)) $html .= call_user_func_array($this->formatFunction, [$data, $this, $item]);
        return $html; ;
    }

    public function setFormatFunction($value)
    {
        $this->formatFunction = $value;
        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

     /**
     * Get the value of name
     */
    public function getSafeName()
    {
        return $this->as ? $this->as : str_replace('.', '__', $this->name);
    }

    /**
     * Get the value of as
     */
    public function getAs()
    {
        return $this->as;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the value of search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Get the value of defs
     */
    public function getDefs()
    {
        return $this->defs;
    }

    /**
     * Get the value of system
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Get the value of noSelect
     */
    public function getNoSelect()
    {
        return $this->noSelect;
    }

    /**
     * Get the value of sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Get the value of width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the value of as
     *
     * @return  self
     */
    public function setAs($as)
    {
        $this->as = $as;

        return $this;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function setSearch($value)
    {
        $this->search = $value;
        return $this;
    }

    public function setDefs($value)
    {
        $this->defs = $value;
        return $this;
    }

    public function setSystem($value)
    {
        $this->system = $value;
        return $this;
    }

    public function setNoSelect($value)
    {
        $this->noSelect = $value;
        return $this;
    }

    public function setWidth($value)
    {
        $this->width = $value;
        return $this;
    }

    public function setSort($value)
    {
        $this->sort = $value;
        return $this;
    }



    /**
     * Get the value of sortDesc
     */
    public function getSortDesc()
    {
        return $this->sortDesc;
    }

    /**
     * Set the value of sortDesc
     *
     * @return  self
     */
    public function setSortDesc($sortDesc)
    {
        $this->sortDesc = $sortDesc;

        return $this;
    }
}
