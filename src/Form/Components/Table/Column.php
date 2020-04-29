<?php

namespace dimonka2\flatform\Form\Components\Table;

class Column
{
    protected $name;           // field name, mapped as "data"
    protected $title;          // column title
    protected $search;         // enables search
    protected $sort;           // disable sort by this column
    protected $system;         // virtual field without sort and search
    protected $class;          // field class
}
