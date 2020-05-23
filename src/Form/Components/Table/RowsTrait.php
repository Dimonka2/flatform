<?php

namespace dimonka2\flatform\Form\Components\Table;

use Illuminate\Support\Fluent;

trait RowsTrait
{
    protected $rows;   // collection of DTRow objects

    protected function createRows(array $rows)
    {
        $this->rows = new Rows($this);
        foreach($rows as $row) {
            $this->addRow($row);
        }
    }

    public function addRow(array $definition)
    {
        $row = new Fluent($definition);
        $this->rows[] = $row;
        return $row;
    }

    public function getRow($index): ?Fluent
    {
        return $this->rows->getRow($index);
    }

    /**
    * Get the value of Rows
    */
    public function getRows()
    {
        return $this->rows;
    }

    protected function addOrderLinks()
    {
        if($this->order) {
            $order = str_replace('=', ',', http_build_query($this->order, null, '|'));
            $this->models->appends([$this->id . '-o' => $order]);
        }
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
                $fields[] =  $field->name . ($field->as ? ' as ' . $field->as : '' );
            }
        }

        if($this->order){
            foreach($this->order as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        $query = $query->addSelect($fields);
        $items = $query->paginate($this->length, ['*'], $this->id . '-p', $this->page);

        $this->page = $items->currentPage();
        $this->models = $items;
        $this->addOrderLinks();
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
                $nestedData[ $column->name ] = $value;
            }
            $this->addRow($nestedData);
        }

    }

}
