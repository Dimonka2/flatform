<?php
namespace dimonka2\flatform\Form\Components\Table;

interface IColumnFormat
{
    function __invoke($value, $column, $row);
}
