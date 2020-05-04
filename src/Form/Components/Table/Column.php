<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Contracts\IDataProvider;

class Column implements IDataProvider
{
    use SettingReaderTrait;

    protected $name;            // field name, mapped as "data"
    protected $title;           // column title
    protected $search;          // enables search
    protected $sort;            // disable sort by this column
    protected $system;          // virtual field without sort and search
    protected $class;           // field class
    protected $hide;            // this column is not displayed
    protected $noSelect;        // special case for some columns there is no need to add a select, like "count"
    protected $as;              // all nested columns will get an automatic "as" synonym
    protected $format;          // column format

    private $table;
    private $row; // for idataprovider getdata

    public function read(array $definition)
    {
        $format = $this->readSingleSetting($definition, 'format');
        if($format) {
            if(is_callable($format)) {
                $this->format = $format;
            } else {
                $this->format = new ElementContainer([], $this->table->getContext());
                $this->format->readItems($format);
            }
        }

        $definition = ElementFactory::preprocessElement($definition, false);
        $this->readSettings($definition, [
            'name', 'title', 'search', 'sort', 'system', 'class', 'hide',
        ]);
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function getSafeName()
    {
        return $this->as ? $this->as : str_replace('.', '__', $this->name);
    }

    public function hasFormat()
    {
        return !!$this->format;
    }

    public function format($value, $row)
    {
        $html = '';
        if($this->format) {
            if (is_callable($this->format)) {
                $html = call_user_func_array($this->format,
                    [$value, $this, $row]);
            } else {
                $this->row = $row;
                $context = $this->table->getContext();
                $context->setDataProvider($this);
                $html = $this->format->renderElement();
                $context->setDataProvider(null);
            }
        }
        return $html;
    }

    public function getData($name)
    {
        if(!$this->row) return;
        if(is_integer($name)) $name = $this->getSafeName();
        if($name == '_item') return $this->row;
        return $this->row->{$name} ?? null;
    }
}
