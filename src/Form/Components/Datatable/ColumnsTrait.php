<?php

namespace dimonka2\flatform\Form\Components\Datatable;

use dimonka2\flatform\Form\Components\Datatable\DTColumn;

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
        $column = $this->createElement($definition, 'dt-column');
        $this->columns[] = $column;
        return $column;
    }

    public function getColumn($index): ?DTColumn
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
