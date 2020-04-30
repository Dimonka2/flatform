<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\ElementContainer;

class Table extends ElementContainer
{
    use ColumnsTrait;
    use RowsTrait;

    protected $order;
    protected $start;
    protected $length;
    protected $query;
    protected $search;
    protected $select;

    protected $count;

    // use FiltersTrait;

    public function render()
    {
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
        $output = [
            ['row', [
                ['col', ], // search
                ['col', ], // filter/actions
            ]],
            ['div', [
                ['table', [
                    ['thead', 'onReander' => function($item){$this->renderHead();},],
                    ['tbody', 'onReander' => function($item){$this->renderBody();},],
                ]]
            ]],
            ['row', [
                ['col', ], // page length
                ['col', ], // paginator
            ]],
        ];

    }

    protected function renderHead()
    {

    }

    protected function renderBody()
    {

    }

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $rows = self::readSingleSetting($element, 'rows');
        $this->createColumns($rows ?? []);

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
}
