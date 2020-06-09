<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Components\Table\Formatter\ElementMapping;

class Table extends ElementContainer
{
    use ColumnsTrait;
    use RowsTrait;
    use OrderTrait;
    use FiltersTrait;

    protected $page;
    protected $length = 10;
    protected $lengthOptions = [10, 20, 30, 50, 100];
    protected $query;
    protected $search;
    protected $select;
    protected $details;
    protected $formatters = [];     // this is a lookup list for column formatters
    protected $formatFunction;      // this is a table element format function
    protected $info;                // make it false to exclude info column

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
                ['col', 'md' => 4, '+class' => 'p-3',
                    'hide' => !is_null($this->info) && !$this->info,
                    'text' => $this->formatPosition()], // page length
                ['col', 'md' => 8, [
                    ['div', '+class' => 'float-md-right mt-2', 'text' => $this->getLinks()], // paginator
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
        $total = $this->count ? ' (' . $this->count . ')' : '';
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
            'lengthOptions',
            'query',
            'search',
            'formatters',
            'formatFunction',
            'info',
        ]);

        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $rows = self::readSingleSetting($element, 'rows');
        $this->createRows($rows ?? []);


        $filters = self::readSingleSetting($element, 'filters');
        $this->createFilters($filters ?? []);

        $details = self::readSingleSetting($element, 'details');
        if(is_array($details)) $this->createDetails($details);

//        $select = self::readSingleSetting($element, 'select');
//        if(is_array($select)) $this->createSelect($select);


        if($this->order) $this->preprocessOrder();

        parent::read($element);
        $this->requireID();
    }


    public function getColumnFormatter($name)
    {
        if(is_string($name) && ($this->formatters[$name] ?? false)) return $this->formatters[$name];
        return ElementMapping::map($name) ;
    }

    protected function createDetails(array $details)
    {
        $this->details = new TableDetails($this);
        $this->details->read(ElementFactory::preprocessElement($details, false));
    }

    public function hasDetails()
    {
        return $this->details && !$this->details->getDisabled();
    }

    public function hasFormat()
    {
        return !!$this->formatFunction;
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
        $this->preprocessOrder();
        return $this;
    }

    /**
     * Get the value of length
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the value of length
     *
     * @return  self
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    public function getLinks()
    {
        return $this->models ? $this->models->links() : null;
    }

    /**
     * Get the value of filter
     */
    public function getFilter()
    {
        return $this->filters;
    }

    /**
     * Set the value of filter
     *
     * @return  self
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get the value of formatFunction
     */
    public function getFormatFunction()
    {
        return $this->formatFunction;
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

    /**
     * Get the value of models
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Get the value of search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set the value of search
     *
     * @return  self
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get the value of lengthOptions
     */
    public function getLengthOptions()
    {
        return $this->lengthOptions;
    }

    /**
     * Set the value of lengthOptions
     *
     * @return  self
     */
    public function setLengthOptions($lengthOptions)
    {
        $this->lengthOptions = $lengthOptions;

        return $this;
    }


    /**
     * Get the value of count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the value of details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set the value of details
     *
     * @return  self
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get the value of query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the value of query
     *
     * @return  self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }
}
