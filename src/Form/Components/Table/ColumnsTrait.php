<?php

namespace dimonka2\flatform\Form\Components\Table;

trait ColumnsTrait
{
    protected $columns;   // collection of DTColumn objects

    protected function createColumns(array $columns)
    {
        $this->columns = new Columns($this);
        foreach($columns as $column) {
            $this->addColumn($column);
        }
    }

    public function addColumn(array $definition)
    {
        $column = new Column($this);
        $column->read($definition);
        $this->columns[] = $column;
        return $column;
    }

    public function getColumn($index): ?Column
    {
        return $this->columns->getColumn($index);
    }

    /**
    * Get the value of Columns
    */
    public function getColumns()
    {
        return $this->columns;
    }


}
