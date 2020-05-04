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

    protected function buildRows()
    {
        $query = $this->query;
        $this->count = $query->count();
        $this->filtered_count = $this->count;
        // if ( $this->search) {
        //     $query = self::addSearch($query, $tablesearch, $table);
        //     $this->filtered_count = $query->count();
        // }

        // $query = $query->limit($this->length)->offset($this->start ?? 0);

        // add select
        $fields = [];
        foreach($this->columns as $field) {
            if (!$field->noSelect && !$field->system) {
                $fieldName = $field->name . ($field->as ? ' as ' . $field->as : '' );
                $fields[] = $fieldName;
                // $query = $query->addSelect($fieldName);
            }
        }
        $query = $query->addSelect($fields);
        $items = $query->paginate($this->length);
        $this->models = $items;
        if ($this->getAttribute('debug') && \App::environment('local')) {
            debug($query->toSql() );
            debug($items);
        }
        foreach ($items as $item) {
            $nestedData = [];
            foreach($this->columns as $column) {
                $value = '';
                if (!$column->system) {
                    $value = $item->{$column->as ? $column->as : $column->name};
                }
                $field = $column->getSafeName();
                if($column->hasFormat()) {
                    $nestedData[ $column->name ] = $column->format($value, $item);
                } else if($this->hasFormatter()) {
                    $nestedData[ $column->name ] = $this->format($value, $item, $column);
                } else  $nestedData[ $field ] = $value;
            }
            $this->addRow($nestedData);
        }

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
