<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Components\Table\Formatter\ElementMapping;
use dimonka2\flatform\Form\ElementContainer;

class Table extends ElementContainer
{
    use ColumnsTrait;
    use RowsTrait;

    protected $order;   // generic format ['column.name' => 'asc'], but could be just 'column.name'
    protected $page;
    protected $length = 10;
    protected $query;
    protected $search;
    protected $select;
    protected $formatters = []; // this is a lookup list for column formatters

    protected $count;
    protected $filtered_count;
    protected $models;

    // use FiltersTrait;

    public function render()
    {
        // build rows
        if($this->query) $this->buildRows();
        /* 1. render surrounding:
                Search,
                pagination,
                length choice,
                filters,
                actions
            2. render table:
                Header columns with sorting and check all,
                body rows with checks and details
        */
        $declaration = [
            ['row', [
                ['col', ], // search
                ['col', ], // filter/actions
                ['col', 'col' => 12, [
                    ['table',
                        'id' => $this->id,
                        'class' => $this->class,
                        [
                            ['thead', [ ['tr', [
                                ['onRender' => function($item){return $this->renderHead();},]
                                ]]
                            ]],
                            ['tbody', [
                                ['onRender' => function($item){return $this->renderBody();},]
                            ]],
                        ]
                    ],
                ]],
                ['col', 'md' => 4, '+class' => 'p-3', 'text' => $this->formatPosition()], // page length
                ['col', 'md' => 8, [
                    ['div', '+class' => 'float-md-right mt-2', 'text' => $this->models->links()], // paginator
                ]],
            ]],
        ];

        return FlatformService::render($declaration);
    }

    protected function renderHead()
    {
        return $this->columns->render($this->context);
    }

    protected function renderBody()
    {
        return $this->rows->render($this->context);
    }

    protected function formatPosition()
    {
        $page = $this->page ?? 0;
        $count = count($this->rows);
        $total = ' (' . $this->count . ')';
        if($count == 0) return $total;
        $start = (($page - 1) * $this->length + 1);
        return  $start . '-' . ($start + $count - 1)  . $total;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'order',
            'page',
            'length',
            'query',
            'search',
            'formatters',
        ]);

        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $rows = self::readSingleSetting($element, 'rows');
        $this->createRows($rows ?? []);

        //        $filters = self::readSingleSetting($element, 'filters');
//        $this->createFilters($filters ?? []);

//        $details = self::readSingleSetting($element, 'details');
//        if(is_array($details)) $this->createDetails($details);

//        $select = self::readSingleSetting($element, 'select');
//        if(is_array($select)) $this->createSelect($select);


        if($this->order) $this->preprocessOrder();

        parent::read($element);
        $this->requireID();
    }

    private function preprocessOrder()
    {
        $order = [];
        $orders = $this->order;
        if(is_string($orders)) $orders = [$orders];
        if(is_array($orders)){
            foreach ($orders as $key => $value) {
                if(is_integer($key)) {
                    $column = $value;
                    $direction = null;
                } else {
                    $column = $key;
                    $direction = $value;
                }

                $index = $this->columns->getColumnIndex($column);
                if($index !== false) {
                    $column = $this->columns[$index];
                    $sort = $column->sort;
                    if($sort ?? true) $order[$column->name] = $direction ?
                            strtoupper($direction) :
                            (strtoupper($sort) == 'DESC' ? 'DESC': 'ASC');
                }
            }
        }

        $this->order = $order;
    }

    public function getColumnFormatter($name)
    {
        if(is_string($name) && ($this->formatters[$name] ?? false)) return $this->formatters[$name];
        return ElementMapping::map($name) ;
    }

    public function hasFormatter()
    {
        return 0;
    }


    /**
     * Get the value of order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
