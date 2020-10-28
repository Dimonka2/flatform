<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table;

interface IColumnFormat
{
    function __invoke($value, $column, $row);
}
