<?php

namespace dimonka2\flatform\Form\Components\Table;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function addRow($definition)
    {
        if($definition instanceof Fluent) {
            $row = $definition;
        } else {
            $row = new Fluent($definition);
        }
        $this->rows[] = $row;
        return $row;
    }

    public function getRow($index): ?Fluent
    {
        return $this->rows->getRow($index);
    }

    public function getRowCount()
    {
        return $this->rows->count();
    }

    /**
    * Get the value of Rows
    */
    public function getRows()
    {
        return $this->rows;
    }

    public function getVisibleValues($fieldName)
    {
        $values = [];
        foreach($this->rows as $row){
            $values[] = $row[$fieldName] ?? null;
        }
        return $values;
    }

    protected function addOrderLinks()
    {
        if($this->order) {
            $order = str_replace('=', ',', http_build_query($this->order, null, '|'));
            $this->models->appends([$this->id . '-o' => $order]);
        }
    }

    protected function addSearch($query, $term)
    {
        $tablesearch = '%' . $term . '%';
        $query = $query->where(function($q) use ($tablesearch) {
            foreach($this->columns as $column) {
                if ($column->search) {
                    $q = $q->orWhere( $column->name, 'like', $tablesearch );
                }
            }
        });

        return $query;
    }

    public function buildRows()
    {
        $query = $this->query;
        if(!$query) return;
        $this->rows->clear();

        $this->count = $query->count();

        // apply filters
        foreach($this->filters as $filter){
            if(!$filter->getDisabled()){
                $filter->apply($query, $filter->getValue());
            }
        }

        if ( $this->search) {
            $query = $this->addSearch($query, $this->search);
        }

        // $query = $query->limit($this->length)->offset($this->start ?? 0);

        // add select
        if($this->addSelect) {
            $fields = $this->addSelect;
        } else {
            $fields = [];
        }

        foreach($this->columns as $field) {
            if (!$field->noSelect && !$field->system) {
                if($field->raw) {
                    $fields[] = DB::raw($field->raw . ($field->as ? ' as ' . $field->as : '' ) );
                } else {
                    $fields[] =  $field->name . ($field->as ? ' as ' . $field->as : '' );
                }
            }
        }

        if($this->order){
            foreach($this->order as $column => $direction) {
                $query = $query->orderBy($column, $direction);
            }
        }

        $query = $query->addSelect($fields);
        $items = $query->paginate($this->length, ['*'], $this->id . '-p', $this->page);
        $this->filtered_count = $items->total();

        $this->page = $items->currentPage();
        $this->models = $items;
        $this->addOrderLinks();
        if ($this->getAttribute('debug') && \App::environment('local')) {
            debug($query->toSql() );
            debug($items);
        }

        foreach ($items as $item) {
            $nestedData = new Fluent();
            foreach($this->columns as $column) {
                $value = '';
                if (!$column->system ) {
                    $value = $item->{$column->as ? $column->as : $column->name};
                }
                $nestedData[ $column->name ] = $value;
            }
            if($this->addSelect) {
                foreach ($this->addSelect as $columnName) {
                    if(strpos($columnName, ' as ')) {
                        [$name, $alias] = preg_split('/\s+as\s+/', $columnName);
                        $nestedData[$name] = $item->{$alias};
                    } else {
                        $nestedData[$columnName] = $item->{
                            strpos($columnName, '.') ? Str::afterLast($columnName, '.') : $columnName
                        };
                    }
                }
            }
            $nestedData[ '_item'] = $item;
            $this->addRow($nestedData);
        }
        $this->processSelection();
    }

}
