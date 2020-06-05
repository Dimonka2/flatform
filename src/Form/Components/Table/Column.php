<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Contracts\IDataProvider;
use dimonka2\flatform\Form\Contracts\IElement;

class Column implements IDataProvider
{
    use SettingReaderTrait;

    protected $name;            // field name, mapped as "data"
    protected $title;           // column title
    protected $search;          // enables search
    protected $sort;            // disable sort by this column or make it "DESC"
    protected $system;          // virtual field without sort and search
    protected $class;           // field class
    protected $hide;            // this column is not displayed
    protected $noSelect;        // special case for some columns there is no need to add a select, like "count"
    protected $as;              // all nested columns will get an automatic "as" synonym
    protected $format;          // column format: callable, container, template name or IColumnFormat
    protected $width;           // column width

    private $table;
    private $row; // for idataprovider getdata

    public function read(array $definition)
    {
        $format = $this->readSingleSetting($definition, '_format');
        if($format) {
            $this->format = $this->table->getColumnFormatter($format);
        }
        $format = $this->readSingleSetting($definition, 'format');
        if($format) {
            if(is_callable($format) || is_object($format)) {
                $this->format = $format;
            } else {
                $this->format = new ElementContainer([], $this->table->getContext());
                $this->format->readItems($format);
            }
        }

        $definition = ElementFactory::preprocessElement($definition, false);

        $this->readSettings($definition, [
            'name', 'title', 'search', 'sort', 'system', 'class', 'hide', 'width',
        ]);
        if($this->sort === null) $this->sort = !$this->system;
    }

    public function renderDefinition($order)
    {
        $def = ['th'];
        $def['class'] = 'text-nowrap text-truncate ';
        if($this->width) $def['style'] = 'width: ' . $this->width . ';';
        if ($this->sort) {
            switch ($order[$this->name] ?? null) {
                case 'ASC':
                    $sortingClass = 'fa fa-sort-up text-danger';
                    break;
                case 'DESC':
                    $sortingClass = 'fa fa-sort-down text-danger';
                    break;

                default:
                    $sortingClass = 'fa fa-sort text-slate-300';
                    break;
            }
            $def['items'] = [
                ['a', 'href' => '#', 'class' => 'd-block',[
                    ['div', 'class' => 'float-right d-block ml-2', 'style' => '', [
                        ['i', 'class' => 'text-nowrap ' . $sortingClass],
                    ]],
                    ['div', 'class' => 'text-slate-600 d-inline-block mr-3 '  . $this->class,
                    'text' => $this->table->renderItem($this->title)],
                ]],
            ];
        } else {
            $def['text'] = $this->table->renderItem($this->title);
        }
        return $def;
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

    public function doFormat($value, $row)
    {
        $html = '';
        $format = $this->format;
        if($format) {
            if(is_object($format)) {
                if($format instanceof \Closure) {
                    $html = $format($value, $this, $row);
                }elseif($format instanceof IElement) {
                    $this->row = $row;
                    $context = $this->table->getContext();
                    $context->setDataProvider($this);
                    $html = $this->format->renderElement();
                    $context->setDataProvider(null);
                } elseif ($format instanceof IColumnFormat){
                    // use only invoke function of the object
                    $html = $format($value, $this, $row);
                }
            }elseif (is_callable($format)) {
                $html = call_user_func_array($format, [$value, $this, $row]);

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

    /**
     * Set the value of format
     *
     * @return  self
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function visible()
    {
        return !$this->hide;
    }
}
