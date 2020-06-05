<?php
namespace dimonka2\flatform\Form\Components\Table;

trait OrderTrait
{
    protected $order;   // generic format ['column.name' => 'asc'], but could be just 'column.name'

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
                $sort = $this->getColumnSort($column);
                if($sort) {
                    $order[$column] = $direction ? strtoupper($direction) : $sort;
                }
            }
        }

        $this->order = $order;
    }

    public function getColumnSort($columnName)
    {
        $index = $this->columns->getColumnIndex($columnName);
        if($index === false) return false;
        $column = $this->columns[$index];
        $sort = $column->sort;
        if(!$sort) return false;
        return (strtoupper($sort) == 'DESC' ? 'DESC': 'ASC');

    }

}
