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


}
