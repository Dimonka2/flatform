<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementContainer;

class Table extends ElementContainer
{
    use ColumnsTrait;
    use RowsTrait;

    protected $order;
    protected $start;
    protected $length = 10;
    protected $query;
    protected $search;
    protected $select;

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
                ['col', 'text' => $this->formatPosition()], // page length
                ['col', 'text' => $this->models->links()], // paginator
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
        $start = $this->start ?? 0;
        $count = count($this->rows);
        $total = ' (' . $this->count . ')';
        if($count == 0) return $total;
        return ($start + 1) . '-' . ($start + $count)  . $total;
    }

    protected function read(array $element)
    {
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

        $this->readSettings($element, [
            'order',
            'start',
            'length',
            'query',
            'search',
        ]);

        parent::read($element);
        $this->requireID();
    }

    public function hasFormatter()
    {
        return 0;
    }

}
